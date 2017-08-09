<?php

namespace Rojr\Scaffold\Email\Templates\Models;

use Rhubarb\Stem\Schema\SolutionSchema;

class EmailTemplateSchema extends SolutionSchema
{
    public function __construct()
    {
        parent::__construct(0.1);

        $this->addModel('EmailTemplate', EmailTemplate::class);
    }
}
