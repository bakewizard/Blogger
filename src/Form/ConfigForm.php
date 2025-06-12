<?php
declare(strict_types=1);

namespace Blogger\Form;

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Override;

/**
 * Blog Config Form.
 */
class ConfigForm extends Form
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('articlesPerPage', ['type' => 'integer', 'default' => 12])
                        ->addField('comments.sorting', ['type' => 'scalar', 'default' => 'desc'])
                        ->addField('comments.depth', ['type' => 'integer', 'default' => 5])
                        ->addField('comments.registration', ['type' => 'boolean', 'default' => 1])
                        ->addField('comments.moderation', ['type' => 'boolean', 'default' => 1])
                        ->addField('comments.notification', ['type' => 'boolean', 'default' => 0]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validationDefault(Validator $validator): Validator
    {
        return $validator->nonNegativeInteger('articlesPerPage')
                        ->addNested('comments', (new Validator())
                                ->scalar('sorting')
                                ->nonNegativeInteger('depth')
                                ->boolean('registration')
                                ->boolean('moderation')
                                ->boolean('notification'));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function _execute(array $data): bool
    {
        Configure::write($data);

        return Configure::dump('Blogger', 'db', array_keys($data));
    }
}
