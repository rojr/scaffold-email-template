<?php

namespace Rojr\Scaffold\Email\Templates\Models;

use Rhubarb\Crown\String\StringTools;
use Rhubarb\Leaf\Table\Leaves\Columns\BooleanColumn;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\AndGroup;
use Rhubarb\Stem\Filters\Equals;
use Rhubarb\Stem\Filters\Not;
use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\ForeignKeyColumn;
use Rhubarb\Stem\Schema\Columns\LongStringColumn;
use Rhubarb\Stem\Schema\Columns\StringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

class EmailTemplate extends Model
{
    protected function createSchema()
    {
        $schema = new ModelSchema('tblEmailTemplate');

        $schema->addColumn(
            new AutoIncrementColumn('EmailTemplateID'),
            new StringColumn('ClassName', 255),
            new StringTools('Subject', 500),
            new StringTools('FromAddress', 100),
            new LongStringColumn('Content'),
            new ForeignKeyColumn('ParentEmailTemplate'),
            new BooleanColumn('IsBase')
        );

        return $schema;
    }

    protected function getConsistencyValidationErrors()
    {
        $errors = parent::getConsistencyValidationErrors();

        try {
            self::find(
                new AndGroup(
                    [
                        new Equals('IsBase', true),
                        new Not(new Equals($this->getUniqueIdentifierColumnName(), $this->getUniqueIdentifier())),
                    ]
                ));

            $errors['IsBase'] = 'Base template already set. There can only be one.';
        } catch (RecordNotFoundException $ex) {
        }

        return $errors;
    }
}
