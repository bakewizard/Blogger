<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * Categories Model
 *
 * @method \Blogger\Model\Entity\Category newEmptyEntity()
 * @method \Blogger\Model\Entity\Category newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Category> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\Category get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\Category findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \Blogger\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Category> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Category|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Category saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Category>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Category> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Category>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Category> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 * @property \Blogger\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $ParentCategories
 * @property \Blogger\Model\Table\CategoriesTable&\Cake\ORM\Association\HasMany $ChildCategories
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $BloggerCategoriesI18n
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsToMany $Articles
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @extends \Cake\ORM\Table<array{Translate: \Cake\ORM\Behavior\TranslateBehavior, Tree: \Cake\ORM\Behavior\TreeBehavior}>
 */
class CategoriesTable extends Table
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

        $this->setTable('blogger_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentCategories', [
            'className' => 'Blogger.Categories',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildCategories', [
            'className' => 'Blogger.Categories',
            'foreignKey' => 'parent_id',
        ]);

        $this->belongsToMany('Articles', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'article_id',
            'joinTable' => 'blogger_articles_categories',
            'className' => 'Blogger.Articles',
        ]);

        $this->addBehavior('Tree');
        $this->addBehavior('Translate', [
            'fields' => ['name', 'description', 'seo_title', 'seo_description', 'seo_keywords'],
            'translationTable' => 'BloggerCategoriesI18n',
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
                ->scalar('name')
                ->maxLength('name', 100)
                ->requirePresence('name', 'create')
                ->notEmptyString('name');

        $validator
                ->scalar('description')
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCategories'));

        return $rules;
    }
}
