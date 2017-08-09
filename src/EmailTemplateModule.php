<?php

namespace Rojr\Scaffold\Email\Templates;

use Rhubarb\Crown\Module;
use Rhubarb\Stem\Schema\SolutionSchema;
use Rojr\Scaffold\Email\Templates\Models\EmailTemplateSchema;

class EmailTemplateModule extends Module
{
    protected function initialise()
    {
        SolutionSchema::registerSchema('CmsDatabase', EmailTemplateSchema::class);
    }
}
