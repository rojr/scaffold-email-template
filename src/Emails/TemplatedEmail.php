<?php

namespace Rojr\Scaffold\Email\Templates\Emails;

use Rhubarb\Crown\Sendables\Email\TemplateEmail;
use Rojr\Scaffold\Email\Templates\Exceptions\NoEmailTemplateFound;
use Rojr\Scaffold\Email\Templates\Models\EmailTemplate;

abstract class TemplatedEmail extends TemplateEmail
{
    private $templateObject = null;
    /** @var EmailTemplate|null  */
    private $childTemplate = null;

    private $requiresParent = false;

    public function __construct($childTemplate = null)
    {
        $this->templateObject = EmailTemplate::getEmailTemplateFromClassPath(get_class($this));

        if (!$this->templateObject) {
            throw new NoEmailTemplateFound();
        }

        $this->childTemplate = $childTemplate;
        $recipientData = [];

        if ($this->templateObject->ParentEmailTemplateID != 0) {
            $this->requiresParent = true;
        }

        if ($this->childTemplate) {
            $recipientData['ChildContent'] = $this->childTemplate->getTemplatedEmail()->getHtml();
        } else {
            $recipientData['ChildContent'] = '';
        }

        //TODO add model loading, so values get auto inserted

        parent::__construct($recipientData);
    }

    protected function getTextTemplateBody()
    {
        return strip_tags($this->getTextTemplateBody());
    }

    protected function getHtmlTemplateBody()
    {
        if (!$this->requiresParent) {
            return $this->templateObject->Content;
        } else {
            return $this->templateObject->ParentTemplate->getTemplatedEmail()->getHtml();
        }
    }

    protected function getSubjectTemplate()
    {
        return $this->templateObject->Subject;
    }

    public static abstract function getDefaultHtml();
    public static abstract function isBase();
}
