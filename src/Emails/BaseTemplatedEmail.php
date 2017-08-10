<?php

namespace Rojr\Scaffold\Email\Templates\Emails;

class BaseTemplatedEmail extends TemplatedEmail
{
    public static function getDefaultHtml()
    {
        return '<div>Hot Damn</div>';
    }

    public static function isBase()
    {
        return true;
    }
}
