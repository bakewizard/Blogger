<?php

declare(strict_types=1);

namespace Blogger\Form\Cell;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * CellConfig Form.
 */
class TagCellConfigForm extends Form
{

    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    #[\Override]
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('numberOfTagsToShow', ['type' => 'integer', 'default' => 5]);
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    #[\Override]
    public function validationDefault(Validator $validator): Validator
    {
        return $validator->nonNegativeInteger('numberOfTagsToShow')->requirePresence('numberOfTagsToShow');
    }
}
