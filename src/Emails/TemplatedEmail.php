<?php

namespace Rojr\Scaffold\Email\Templates\Emails;

use Rhubarb\Crown\Sendables\Email\TemplateEmail;
use Rojr\Scaffold\Email\Templates\Exceptions\NoEmailTemplateFound;
use Rojr\Scaffold\Email\Templates\Models\EmailTemplate;

abstract class TemplatedEmail extends TemplateEmail
{
    private $templateObject = null;

    public function __construct(array $recipientData)
    {
        parent::__construct($recipientData);

        $this->templateObject = EmailTemplate::getEmailTemplateFromClassPath(get_class($this));

        if (!$this->templateObject) {
            throw new NoEmailTemplateFound();
        }
    }

    protected function getTextTemplateBody()
    {
    }

    protected function getHtmlTemplateBody()
    {
    }

    protected function getSubjectTemplate()
    {
    }

    protected function printChildTemplate()
    {}

    public static abstract function getDefaultHtml();
    public static abstract function isBase();
}
