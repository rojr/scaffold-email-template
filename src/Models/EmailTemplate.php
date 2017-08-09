<?php

namespace Rojr\Scaffold\Email\Templates\Models;

use Rhubarb\Crown\String\StringTools;
use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\LongStringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

class EmailTemplate extends Model
{
    protected function createSchema()
    {
        $schema = new ModelSchema('tblEmailTemplate');

        $schema->addColumn(
            new AutoIncrementColumn('EmailTemplateID'),
            new StringTools('Subject', 500),
            new StringTools('FromAddress', 100),
            new LongStringColumn('Content')
        );

        return $schema;
    }
}
