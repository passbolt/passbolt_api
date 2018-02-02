<?php
namespace Passbolt\Tags\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Tags Model
 *
 * @property \App\Model\Table\ResourcesTable|\Cake\ORM\Association\BelongsToMany $Resources
 *
 * @method \App\Model\Entity\Tag get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tag newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tag|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tag findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TagsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Resources', [
            'foreignKey' => 'tag_id',
            'targetForeignKey' => 'resource_id',
            'joinTable' => 'resources_tags'
        ]);

        $this->hasMany('ResourcesTags', [
            'foreignKey' => 'tag_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 128)
            ->requirePresence('slug', 'create')
            ->notEmpty('slug');

        $validator
            ->boolean('is_shared')
            ->requirePresence('is_shared', 'create')
            ->notEmpty('is_shared');

        return $validator;
    }

    /**
     *
     * @param string $userId uuid
     * @return Query
     */
    public function findIndex($userId) {

        $resources = $this->Resources->findIndex($userId);
        $resourcesId = Hash::extract($resources->toArray(), '{n}.id');

        $tags = $this->find();
        $tags->innerJoinWith('ResourcesTags', function ($q) use ($resourcesId) {
            return $q->where(['ResourcesTags.resource_id IN' => $resourcesId]);
        })
        ->order('slug')
        ->distinct();

        return $tags;
    }
}
