<?php

namespace Rojr\Scaffold\Email\Templates;

use Rhubarb\Crown\Module;
use Rhubarb\Stem\Schema\SolutionSchema;
use Rojr\Scaffold\Email\Templates\Emails\BaseTemplatedEmail;
use Rojr\Scaffold\Email\Templates\Emails\TemplatedEmail;
use Rojr\Scaffold\Email\Templates\Models\EmailTemplateSchema;

class EmailTemplateModule extends Module
{
    /** @var TemplatedEmail[] $registeredEmailTemplates */
    private static $registeredEmailTemplates = [];

    protected function initialise()
    {
        SolutionSchema::registerSchema('EmailTemplateDatabase', EmailTemplateSchema::class);

        self::registerEmailTemplate(BaseTemplatedEmail::class);
    }

    public static function registerEmailTemplate($emailClass)
    {
        if (!class_exists($emailClass)) return;

        if ($emailClass::isBase()) {
            if (count(self::$registeredEmailTemplates)) {
                foreach (self::$registeredEmailTemplates as $key => $template) {
                    self::$registeredEmailTemplates[$key] = $emailClass;
                    return;
                }
            }
        }

        self::$registeredEmailTemplates[] = $emailClass;
    }

    public static function replaceEmailTemplate($original, $replaceWith)
    {
        if (!(class_exists($original) && class_exists($replaceWith))) return;

        foreach (self::$registeredEmailTemplates as $key => $template) {
            if ($original === $template) {
                self::$registeredEmailTemplates[$key] = $replaceWith;
                break;
            }
        }
    }

    public static function getRegisteredEmailTemplates()
    {
        return self::$registeredEmailTemplates;
    }
}
