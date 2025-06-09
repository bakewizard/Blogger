<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Override;

/**
 * ArticlesCategories Model
 *
 * @property &\Cake\ORM\Association\BelongsTo $BloggerArticles
 * @property &\Cake\ORM\Association\BelongsTo $BloggerCategories
 * @method \Blogger\Model\Entity\ArticlesCategory get($primaryKey, $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory newEntity($data = null, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory[] newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesCategory findOrCreate($search, callable $callback = null, $options = [])
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
