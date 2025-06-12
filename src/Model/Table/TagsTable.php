<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * Tags Model
 *
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsToMany $Articles
 * @method \Blogger\Model\Entity\Tag get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\Tag newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Tag> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\Tag|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Tag saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Tag> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Tag findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $BloggerTagsI18n
 * @method \Blogger\Model\Entity\Tag newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Tag>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Tag> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Tag>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Tag> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @extends \Cake\ORM\Table<array{Translate: \Cake\ORM\Behavior\TranslateBehavior}>
 */
class TagsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blogger_tags');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Articles', [
            'foreignKey' => 'tag_id',
            'targetForeignKey' => 'article_id',
            'joinTable' => 'blogger_articles_tags',
            'className' => 'Blogger.Articles',
        ]);

        $this->addBehavior('Translate', [
            'fields' => ['title'],
            'translationTable' => 'BloggerTagsI18n',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    #[Override]
    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->nonNegativeInteger('id')
                ->allowEmptyString('id', null, 'create');

        $validator
                ->scalar('title')
                ->maxLength('title', 255)
                ->requirePresence('title', 'create')
                ->notEmptyString('title');

        $validator
                ->scalar('alias')
                ->maxLength('alias', 50)
                ->allowEmptyString('alias')
                ->add('alias', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    #[Override]
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['alias']));

        return $rules;
    }
}
