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
    /** @var TemplatedEmail|null */
    private $childTemplatedEmail = null;

    private $requiresParent = false;

    private $recipientData = [];

    public function __construct($childTemplate = null, $recipientData = [])
    {
        $this->templateObject = EmailTemplate::getEmailTemplateFromClassPath(get_class($this));

        if (!$this->templateObject) {
            throw new NoEmailTemplateFound();
        }

        $this->childTemplate = $childTemplate;

        if ($this->templateObject->ParentEmailTemplateID != 0) {
            $this->requiresParent = true;
        }

        if ($this->childTemplate) {
            $this->childTemplatedEmail = $this->childTemplate->getTemplatedEmail();
            $recipientData['ChildContent'] = $this->childTemplatedEmail->getTemplateContent();
        } else {
            $recipientData['ChildContent'] = '';
        }

        $recipientData['Subject'] = $this->getSubject();

        //TODO add model loading, so values get auto inserted

        parent::__construct($recipientData);

        $this->recipientData = $recipientData;
    }

    protected function getTextTemplateBody()
    {
        return strip_tags($this->getHtmlTemplateBody());
    }

    protected function getTemplateContent()
    {
        return $this->templateObject->Content;
    }

    protected function getHtmlTemplateBody()
    {
        if (!$this->requiresParent) {
            return $this->getTemplateContent();
        } else {
            return $this->templateObject->ParentEmailTemplate->getTemplatedEmail($this->templateObject, $this->recipientData)->getHtml();
        }
    }

    protected function getSubjectTemplate()
    {
        return $this->templateObject->Subject;
    }

    public function getSubject()
    {
        if ($this->childTemplate) {
            return $this->childTemplatedEmail->getSubject();
        }
        return parent::getSubject();
    }

    public static abstract function getDefaultHtml();

    public static abstract function isBase();
}
