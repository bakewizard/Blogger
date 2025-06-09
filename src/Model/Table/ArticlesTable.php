<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;
use Search\Manager;

/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Blogger\Model\Entity\Article get($primaryKey, $options = [])
 * @method \Blogger\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \Blogger\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
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
        $this->addBehavior('Search.Search');
        $this->addBehavior('Translate', [
            'fields' => ['title', 'body', 'excerpt', 'seo_title', 'seo_description', 'seo_keywords'],
            'translationTable' => 'BloggerArticlesI18n',
        ]);
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager(): Manager
    {
        $searchManager = $this->behaviors()->Search->searchManager();
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

        return $searchManager;
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

    public function findPublished(SelectQuery $query)
    {
        return $query->where(['published' => true]);
    }

    public function findRelated(SelectQuery $query, ?int $id)
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
                        ->group('article_id')
                        ->orderByDesc('tags_count');
    }

    public function findComments(SelectQuery $query, string $sorting = 'desc')
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
