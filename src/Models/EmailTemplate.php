<?php

namespace Rojr\Scaffold\Email\Templates\Models;

use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\AndGroup;
use Rhubarb\Stem\Filters\Equals;
use Rhubarb\Stem\Filters\Not;
use Rhubarb\Stem\Filters\OneOf;
use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\BooleanColumn;
use Rhubarb\Stem\Schema\Columns\ForeignKeyColumn;
use Rhubarb\Stem\Schema\Columns\LongStringColumn;
use Rhubarb\Stem\Schema\Columns\StringColumn;
use Rhubarb\Stem\Schema\ModelSchema;
use Rojr\Scaffold\Email\Templates\Emails\TemplatedEmail;
use Rojr\Scaffold\Email\Templates\EmailTemplateModule;

/**
 *
 *
 * @property int $EmailTemplateID Repository field
 * @property string $ClassName Repository field
 * @property string $Subject Repository field
 * @property string $FromAddress Repository field
 * @property string $Content Repository field
 * @property int $ParentEmailTemplateID Repository field
 * @property bool $IsBase Repository field
 * @property string $TemplateClassPath Repository field
 * @property-read EmailTemplate[]|\Rhubarb\Stem\Collections\RepositoryCollection $ChildEmailTemplates Relationship
 * @property-read EmailTemplate $ParentEmailTemplate Relationship
 * @property-read TemplatedEmail $TemplatedEmail {@link getTemplatedEmail()}
 */
class EmailTemplate extends Model
{
    protected function createSchema()
    {
        $schema = new ModelSchema('tblEmailTemplate');

        $schema->addColumn(
            new AutoIncrementColumn('EmailTemplateID'),
            new StringColumn('TemplateClassPath', 512),
            new StringColumn('Subject', 500),
            new StringColumn('FromAddress', 100),
            new LongStringColumn('Content'),
            new ForeignKeyColumn('ParentEmailTemplateID'),
            new BooleanColumn('IsBase')
        );

        return $schema;
    }

    protected function getConsistencyValidationErrors()
    {
        $errors = parent::getConsistencyValidationErrors();

        if ($this->IsBase) {
            try {
                self::findFirst(
                    new AndGroup(
                        [
                            new Equals('IsBase', true),
                            new Not(new Equals($this->getUniqueIdentifierColumnName(), $this->getUniqueIdentifier())),
                        ]
                    ));

                $errors[ 'IsBase' ] = 'Base template already set. There can only be one.';
            } catch (RecordNotFoundException $ex) {
            }
        }

        if ($this->TemplateClassPath) {
            if (!class_exists($this->TemplateClassPath)) {
                $errors['TemplateClassPath'] = 'Template Class Path is not a valid Template Object';
            }
        } else {
            $errors['TemplateClassPath'] = 'Template Class Path is required to be set';
        }

        return $errors;
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord() && !$this->ParentEmailTemplateID) {
            try {
                $this->ParentEmailTemplateID = EmailTemplate::findFirst(new Equals('IsBase', true))->UniqueIdentifier;
            } catch (RecordNotFoundException $ex) {
            }
        }
    }

    /**
     * @param TemplatedEmail $childTemplate
     * @param array $recipientData
     * @return TemplatedEmail
     */
    public function getTemplatedEmail($childTemplate = null, $recipientData = [])
    {
        return new $this->TemplateClassPath($childTemplate, $recipientData);
    }

    public static function getEmailTemplateFromClassPath($path) {
        try {
            return self::findFirst(new Equals('TemplateClassPath', $path));
        } catch (RecordNotFoundException $ex) {
            return null;
        }
    }

    public static function checkRecords($oldVersion, $newVersion)
    {
        $templateClassNames = EmailTemplateModule::getRegisteredEmailTemplates();

        //Clean up rougue templates before adding new ones
        foreach (EmailTemplate::find(new Not(new OneOf('TemplateClassPath', $templateClassNames))) as $template) {
            $template->delete();
        }

        foreach ($templateClassNames as $template) {
            if (!self::getEmailTemplateFromClassPath($template)) {
                $emailTemplate = new EmailTemplate();
                $emailTemplate->TemplateClassPath = $template;
                $emailTemplate->IsBase = $template::isBase();
                $emailTemplate->Content = $template::getDefaultHtml();
                $emailTemplate->save();
            }
        }
    }
}
