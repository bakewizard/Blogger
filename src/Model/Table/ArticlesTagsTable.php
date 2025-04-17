<?php

declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

/**
 * ArticlesTags Model
 *
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsTo $Articles
 * @property \Blogger\Model\Table\TagsTable&\Cake\ORM\Association\BelongsTo $Tags
 *
 * @method \Blogger\Model\Entity\ArticlesTag get($primaryKey, $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag newEntity($data = null, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag[] newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag[] patchEntities($entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\ArticlesTag findOrCreate($search, callable $callback = null, $options = [])
 */
class ArticlesTagsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    #[\Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blogger_articles_tags');
        $this->setDisplayField('article_id');
        $this->setPrimaryKey(['article_id', 'tag_id']);

        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
            'className' => 'Blogger.Articles'
        ]);
        $this->belongsTo('Tags', [
            'foreignKey' => 'tag_id',
            'joinType' => 'INNER',
            'className' => 'Blogger.Tags'
        ]);

        $this->addBehavior('CounterCache', [
            'Tags' => ['articles_count']
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    #[\Override]
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['article_id'], 'Articles'));
        $rules->add($rules->existsIn(['tag_id'], 'Tags'));

        return $rules;
    }
}
