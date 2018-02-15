<?php
namespace Passbolt\Tags\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\Collection\CollectionInterface;

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
            'through' => 'ResourcesTags',
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
        $tags->innerJoinWith('ResourcesTags', function (Query $q) use ($resourcesId) {
            return $q->where(['ResourcesTags.resource_id IN' => $resourcesId]);
        })
        ->order('slug')
        ->distinct();

        return $tags;
    }

    /**
     * Decorate a query with the necessary tag finder conditions
     * Usefull to do a contain[tag] and filter[has-tags] on resources for example
     *
     * @param Query $query
     * @param array $options
     * @param string $userId
     * @return Query
     */
    static public function decorateForeignFind(Query $query, array $options, string $userId) {
        // Display the user tags for a given resource
        if (isset($options['contain']['tag'])) {
            $query->contain('Tags', function (Query $q) use ($userId) {
                return $q
                    ->order(['slug'])
                    ->where(function (QueryExpression $where) use ($userId) {
                    return $where->or_(function (QueryExpression $or) use ($userId) {
                        return $or
                            ->eq('ResourcesTags.user_id', $userId)
                            ->isNull('ResourcesTags.user_id');
                    });
                });
            });
            // Remove join data
            $query->formatResults(function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    foreach ($row->tags as $i => $tag) {
                        unset($row->tags[$i]['_joinData']);
                    }
                    return $row;
                });
            });

        }

        // Filter resources by tags
        if (isset($options['filter']['has-tags'])) {
            $tags = $options['filter']['has-tags'];
            $query
                ->innerJoinWith('Tags', function (Query $q) use ($tags) {
                    return $q->where(['Tags.slug IN' => $tags]);
                })
                ->distinct();
        }
        return $query;
    }
}
