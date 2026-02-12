<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Blogger\Model\Entity\Article get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\Article newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Article> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Article> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Article findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Blogger\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsToMany $Categories
 * @property \Blogger\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 * @property \Blogger\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \Blogger\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $ApprovedComments
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $BloggerArticlesI18n
 * @method \Blogger\Model\Entity\Article newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Article>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Article> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Article>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Article> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Search\Model\Behavior\SearchBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @extends \Cake\ORM\Table<array{Search: \Search\Model\Behavior\SearchBehavior, Timestamp: \Cake\ORM\Behavior\TimestampBehavior, Translate: \Cake\ORM\Behavior\TranslateBehavior}>
 */
class ArticlesTable extends Table
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blogger_articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER',
            'className' => 'Users',
        ]);
        $this->belongsToMany('Blogger.Categories', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'blogger_articles_categories',
            'through' => 'Blogger.ArticlesCategories',
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('Blogger.Tags', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'blogger_articles_tags',
            'through' => 'Blogger.ArticlesTags',
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Blogger.Comments', [
            'joinType' => 'INNER',
            'foreignKey' => 'article_id',
        ]);
        $this->hasMany('Blogger.ApprovedComments', [
            'foreignKey' => 'article_id',
            'className' => 'Blogger.Comments',
            'conditions' => ['approved' => true],
            'sort' => 'ApprovedComments.created desc',
        ]);

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', [
            'fields' => ['title', 'body', 'excerpt', 'seo_title', 'seo_description', 'seo_keywords'],
            'translationTable' => 'BloggerArticlesI18n',
        ]);
        $this->addBehavior('Search.Search');

        $this->setFilters();
    }

    /**
     * Configures search filters for the Articles table.
     *
     * @return void
     */
    public function setFilters(): void
    {
        /** @var \Search\Model\Behavior\SearchBehavior $search */
        $search = $this->getBehavior('Search');
        $searchManager = $search->searchManager();

        $searchManager
            ->useCollection('backend')
            ->like('title', ['before' => true, 'after' => true])
            ->compare('created_from', ['fields' => ['created'], 'operator' => '>='])
            ->compare('created_to', ['fields' => ['created'], 'operator' => '<='])
            ->value('published', ['filterEmpty' => true])
            ->useCollection('frontend')
            ->add('text', 'FullTextFilter', [
                'matchMode' => 'IN BOOLEAN MODE',
                'fields' => ['title', 'body'],
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
            ->maxLength('title', 250)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->add('categories', 'custom', [
                'rule' => function ($value, $context) {
                    return !empty($value['_ids']) && is_array($value['_ids']);
                },
                'message' => __d('blogger', 'Choose at least one category!'),
            ]);

        $validator
            ->scalar('body')
            ->maxLength('body', 16777215)
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('excerpt')
            ->maxLength('excerpt', 16777215)
            ->allowEmptyString('excerpt');

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
            ->nonNegativeInteger('sort_order')
            ->allowEmptyString('sort_order');

        $validator
            ->boolean('published')
            ->notEmptyString('published');

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
        $rules->add($rules->existsIn(['author_id'], 'Users'));

        return $rules;
    }

    /**
     * Finds articles that are published.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPublished(SelectQuery $query): SelectQuery
    {
        return $query->where(['published' => true]);
    }

    /**
     * Finds articles related to a specific article by tags.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify.
     * @param int|null $id The ID of the article to find related articles for.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findRelated(SelectQuery $query, ?int $id): SelectQuery
    {
        $subQuery = $this->Tags
            ->find()
            ->select(['id'])
            ->innerJoinWith('Articles', function (SelectQuery $q) use ($id) {
                return $q->where(['article_id =' => $id]);
            });

        return $query
            ->select(['id', 'title', 'tags_count' => $query->func()->count('tag_id')])
            ->innerJoinWith('Tags', function (SelectQuery $q) use ($subQuery) {
                return $q->where(['tag_id IN' => $subQuery]);
            })
            ->where(['article_id !=' => $id])
            ->groupBy('article_id')
            ->orderByDesc('tags_count');
    }

    /**
     * Finds comments for an article, ordered by creation date.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify.
     * @param string $sorting The sorting order, either 'asc' or 'desc'.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findComments(SelectQuery $query, string $sorting = 'desc'): SelectQuery
    {
        return $query->contain([
            'Comments' => function (SelectQuery $q) use ($sorting) {
                return $q->find('threaded')
                    ->orderBy(['Comments.created' => $sorting])
                    ->contain('Users');
            },
        ]);
    }
}
