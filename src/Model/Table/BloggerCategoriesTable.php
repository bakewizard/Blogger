<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BloggerCategories Model
 *
 * @property \Blogger\Model\Table\BloggerCategoriesTable&\Cake\ORM\Association\BelongsTo $ParentBloggerCategories
 * @property \Blogger\Model\Table\BloggerCategoriesTable&\Cake\ORM\Association\HasMany $ChildBloggerCategories
 *
 * @method \Blogger\Model\Entity\BloggerCategory newEmptyEntity()
 * @method \Blogger\Model\Entity\BloggerCategory newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\BloggerCategory> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\BloggerCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\BloggerCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \Blogger\Model\Entity\BloggerCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\BloggerCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\BloggerCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\BloggerCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Blogger\Model\Entity\BloggerCategory>|\Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\BloggerCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Blogger\Model\Entity\BloggerCategory>|\Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\BloggerCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Blogger\Model\Entity\BloggerCategory>|\Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\BloggerCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Blogger\Model\Entity\BloggerCategory>|\Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\BloggerCategory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class BloggerCategoriesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blogger_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentBloggerCategories', [
            'className' => 'Blogger.BloggerCategories',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildBloggerCategories', [
            'className' => 'Blogger.BloggerCategories',
            'foreignKey' => 'parent_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('parent_id')
            ->allowEmptyString('parent_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 16777215)
            ->allowEmptyString('description');

        $validator
            ->scalar('alias')
            ->maxLength('alias', 50)
            ->allowEmptyString('alias');

        $validator
            ->boolean('enabled')
            ->notEmptyString('enabled');

        $validator
            ->scalar('seo_title')
            ->maxLength('seo_title', 160)
            ->allowEmptyString('seo_title');

        $validator
            ->scalar('seo_description')
            ->maxLength('seo_description', 280)
            ->allowEmptyString('seo_description');

        $validator
            ->scalar('seo_keywords')
            ->maxLength('seo_keywords', 100)
            ->allowEmptyString('seo_keywords');

        $validator
            ->nonNegativeInteger('articles_count')
            ->notEmptyString('articles_count');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentBloggerCategories'), ['errorField' => 'parent_id']);

        return $rules;
    }
}
