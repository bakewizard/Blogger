<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Override;

/**
 * ArticlesTags Model
 *
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsTo $Articles
 * @property \Blogger\Model\Table\TagsTable&\Cake\ORM\Association\BelongsTo $Tags
 * @method \Blogger\Model\Entity\ArticlesTag get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\ArticlesTag newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\ArticlesTag> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\ArticlesTag> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @property \Cake\ORM\Table&\Cake\ORM\Association\BelongsTo $TagsJoin
 * @method \Blogger\Model\Entity\ArticlesTag newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesTag>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesTag> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesTag>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\ArticlesTag> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 * @extends \Cake\ORM\Table<array{CounterCache: \Cake\ORM\Behavior\CounterCacheBehavior}>
 */
class ArticlesTagsTable extends Table
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

        $this->setTable('blogger_articles_tags');
        $this->setDisplayField('article_id');
        $this->setPrimaryKey(['article_id', 'tag_id']);

        $this->belongsTo('Blogger.Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Blogger.Tags', [
            'foreignKey' => 'tag_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('CounterCache', [
            'Tags' => ['articles_count'],
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
        $rules->add($rules->existsIn(['tag_id'], 'Tags'));

        return $rules;
    }
}
