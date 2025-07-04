<?php
declare(strict_types=1);

namespace Blogger\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Blogger\Model\Table\ArticlesTable&\Cake\ORM\Association\BelongsTo $Articles
 * @property \Blogger\Model\Table\CommentsTable&\Cake\ORM\Association\BelongsTo $ParentComments
 * @property \Blogger\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $ChildComments
 * @method \Blogger\Model\Entity\Comment newEmptyEntity()
 * @method \Blogger\Model\Entity\Comment newEntity(array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Comment> newEntities(array $data, array $options = [])
 * @method \Blogger\Model\Entity\Comment get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Blogger\Model\Entity\Comment findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \Blogger\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Blogger\Model\Entity\Comment> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Blogger\Model\Entity\Comment|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Blogger\Model\Entity\Comment saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Comment>|false saveMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Comment> saveManyOrFail(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Comment>|false deleteMany(iterable $entities, array $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\Blogger\Model\Entity\Comment> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 * @extends \Cake\ORM\Table<array{CounterCache: \Cake\ORM\Behavior\CounterCacheBehavior, Timestamp: \Cake\ORM\Behavior\TimestampBehavior, Tree: \Cake\ORM\Behavior\TreeBehavior}>
 */
class CommentsTable extends Table
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

        $this->setTable('blogger_comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
        ]);
        $this->belongsTo('Blogger.Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ParentComments', [
            'className' => 'Blogger.Comments',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('Blogger.ChildComments', [
            'className' => 'Blogger.Comments',
            'foreignKey' => 'parent_id',
        ]);

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', [
            'level' => 'level',
        ]);
        $this->addBehavior('CounterCache', [
            'Articles' => ['comments_count'],
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
                ->scalar('author_name')
                ->maxLength('author_name', 255)
                ->allowEmptyString('author_name');

        $validator
                ->scalar('author_email')
                ->maxLength('author_email', 100)
                ->allowEmptyString('author_email');

        $validator
                ->scalar('author_ip')
                ->maxLength('author_ip', 100)
                ->allowEmptyString('author_ip');

        $validator
                ->scalar('content')
                ->requirePresence('content', 'create')
                ->notEmptyString('content');

        $validator
                ->boolean('approved')
                ->notEmptyString('approved');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentComments'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['article_id'], 'Articles'));

        return $rules;
    }
}
