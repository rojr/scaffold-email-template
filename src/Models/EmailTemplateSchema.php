<?php

namespace Rojr\Scaffold\Email\Templates\Models;

use Rhubarb\Stem\Schema\SolutionSchema;

class EmailTemplateSchema extends SolutionSchema
{
    public function __construct()
    {
        parent::__construct(0.2);

        $this->addModel('EmailTemplate', EmailTemplate::class);
    }

    protected function defineRelationships()
    {
        $this->declareOneToManyRelationships(
            [
                'EmailTemplate' => [
                    'ChildEmailTemplates' => 'EmailTemplate.ParentEmailTemplateID:ParentEmailTemplate'
                ]
            ]
        );
    }
}
