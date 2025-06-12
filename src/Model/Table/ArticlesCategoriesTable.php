<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Override;

/**
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsTo $Articles
 * @property \Blogger\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 * @method \Blogger\Model\Entity\ArticlesCategory newEmptyEntity()
 * @method \Blogger\Model\Entity\ArticlesCategory newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\ArticlesCategory> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\ArticlesCategory findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\ArticlesCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesCategory>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesCategory> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 * @extends \Cake\ORM\Table<array{CounterCache: \Cake\ORM\Behavior\CounterCacheBehavior}>
 */
class ArticlesCategoriesTable extends Table
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

        $this->setTable('blogger_articles_categories');
        $this->setDisplayField('article_id');
        $this->setPrimaryKey(['article_id', 'category_id']);

        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
            'className' => 'Blogger.Articles',
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
            'className' => 'Blogger.Categories',
        ]);

        $this->addBehavior('CounterCache', [
            'Categories' => ['articles_count'],
        ]);
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
        $rules->add($rules->existsIn(['article_id'], 'Articles'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
