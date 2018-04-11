<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\Tags\Model\Table;

use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Database\Expression\QueryExpression;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Tags Model
 *
 * @property \App\Model\Table\ResourcesTable|\Cake\ORM\Association\BelongsToMany $Resources
 * @property \Passbolt\Tags\Model\Table\ResourcesTagsTable|\Cake\ORM\Association\HasMany $ResourcesTags
 *
 * @method \App\Model\Entity\Tag get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tag newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tag|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tag findOrCreate($search, callable $callback = null, $options = [])
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
            ->add('slug', 'scalar', [
                'rule' => 'isScalar',
                'last' => true,
                'message' => __('The tag should be a string')
            ])
            ->maxLength('slug', 128, __('Tag can not be more than 128 characters in length.'))
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
    public function findIndex($userId)
    {
        $tags = [];
        $resources = $this->Resources->findIndex($userId);
        $resourcesId = Hash::extract($resources->toArray(), '{n}.id');

        if (!empty($resourcesId)) {
            $tags = $this->find()
                ->innerJoinWith('ResourcesTags', function (Query $q) use ($resourcesId) {
                    return $q->where(['ResourcesTags.resource_id IN' => $resourcesId]);
                })
                ->order('slug')
                ->distinct();
        }

        return $tags;
    }

    /**
     * Decorate a query with the necessary tag finder conditions
     * Usefull to do a contain[tag] and filter[has-tags] on resources for example
     *
     * @param Query $query The query to decorate
     * @param array $options Options
     * @param string $userId The user identifier to decorate for
     * @return Query
     */
    public static function decorateForeignFind(Query $query, array $options, string $userId)
    {
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
        if (isset($options['filter']['has-tag'])) {
            $slug = $options['filter']['has-tag'];
            $query->innerJoinWith('Tags', function ($q) use ($slug, $userId) {
                $isSharedTag = preg_match('/^#/', $slug);
                $q->where(['Tags.slug' => $slug]);
                if (!$isSharedTag) {
                    $q->where(['ResourcesTags.user_id' => $userId]);
                }

                return $q;
            });
        }

        return $query;
    }

    /**
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(\Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (isset($data['slug']) && !empty($data['slug']) && is_string($data['slug'])) {
            $startWith = mb_substr($data['slug'], 0, 1, 'utf-8');
            $data['is_shared'] = ($startWith === '#');
            $data['id'] = UuidFactory::uuid('tag.id.' . $data['slug']);
        }
    }

    /**
     * Build tag entities or fail
     *
     * @param array $tags list of tag slugs
     * @throws BadRequestException if the validation fails
     * @return array $tags list of tag entities
     */
    public function buildEntitiesOrFail(array $tags)
    {
        $collection = [];
        if (!empty($tags)) {
            foreach ($tags as $i => $slug) {
                $collection[$i] = $this->newEntity([
                    'slug' => $slug
                ], [
                    'accessibleFields' => [
                        'id' => true,
                        'slug' => true,
                        'is_shared' => true
                    ]
                ]);
            }
        }
        $errors = [];
        foreach ($collection as $i => $tag) {
            if ($tag->errors()) {
                $errors[$i] = $tag->errors();
            }
        }
        if (!empty($errors)) {
            throw new ValidationRuleException(__('Could not validate the tags.'), $errors);
        }

        return $collection;
    }

    /**
     * Given two arrays of tag Entities this function returns the tags organized by changes
     * example:
     *  current [ 'alpha', '#bravo' ]
     *  new [ '#bravo', 'echo' ]
     *  result [
     *     'created' => ['echo']
     *     'deleted' => ['alpha']
     *     'unchanged' => ['#bravo']
     *
     * @param array $currentTags array of Tag Entity
     * @param array $requestedTags array of Tag Entity
     * @return mixed
     */
    public static function calculateChanges(array $currentTags, array $requestedTags)
    {
        $currentTags = Hash::combine($currentTags, '{n}.id', '{n}');
        $requestedTags = Hash::combine($requestedTags, '{n}.id', '{n}');
        $allTags = Hash::merge($currentTags, $requestedTags);

        $currentIds = array_keys($currentTags);
        $requestIds = array_keys($requestedTags);
        $changes = [
            'deleted' => array_diff($currentIds, $requestIds),
            'created' => array_diff($requestIds, $currentIds),
            'unchanged' => array_intersect($requestIds, $currentIds)
        ];
        $tags = [];
        foreach ($changes as $change => $tagIds) {
            $tags[$change] = [];
            foreach ($tagIds as $tagId) {
                $tags[$change][] = $allTags[$tagId];
            }
        }

        return $tags;
    }

    /**
     * Save an assoc array of tag entity organized by changes
     * for a given resource
     *
     * @param array $tags list of changes from TagsTable::calculateChanges
     * @param resource $resource entity
     * @param string $userId UUID
     * @return bool|mixed
     * @throws \Exception
     */
    public function saveChanges(array $tags, Resource $resource, string $userId)
    {
        // check if user is adding/deleting shared tag and not owner
        if (count($tags['created']) || count($tags['deleted'])) {
            $addedSharedTags = count(Hash::extract($tags['created'], '{n}[is_shared=1]'));
            $removedSharedTags = count(Hash::extract($tags['deleted'], '{n}[is_shared=1]'));
            $isNotOwner = ($resource->permission->type !== Permission::OWNER);
            if (($removedSharedTags || $addedSharedTags) && $isNotOwner) {
                throw new BadRequestException(__('You do not have the permission to edit shared tags on this resource.'));
            }
        }

        // Add all the new tags
        $Tags = $this;
        $ResourcesTags = TableRegistry::get('Passbolt/Tags.ResourcesTags');
        $resourceId = $resource->id;
        $success = $this->getConnection()->transactional(function () use ($userId, $resourceId, $tags, $Tags, $ResourcesTags) {
            // Save all new tags
            foreach ($tags['created'] as $tag) {
                $Tags->save($tag, ['atomic' => false]);
                $resourceData = ['resource_id' => $resourceId, 'tag_id' => $tag->id];
                $options = ['accessibleFields' => ['resource_id' => true, 'tag_id' => true]];
                if (!$tag->is_shared) {
                    $resourceData['user_id'] = $userId;
                    $options['accessibleFields']['user_id'] = true;
                }
                $resourceTag = $ResourcesTags->newEntity($resourceData, $options);
                if (!$ResourcesTags->save($resourceTag, ['atomic' => false])) {
                    return false;
                }
            }

            // Delete all removed tags
            foreach ($tags['deleted'] as $tag) {
                $condition = [
                    'resource_id' => $resourceId,
                    'tag_id' => $tag->id
                ];
                if (!$tag->is_shared) {
                    $condition['user_id'] = $userId;
                }
                if (!$ResourcesTags->deleteAll($condition)) {
                    return false;
                }
            }

            return true;
        });
        $this->deleteAllUnusedTags();

        return $success;
    }

    /**
     * Delete all the unused tags if any
     *
     * @return int number of affected rows
     */
    public function deleteAllUnusedTags()
    {
        // SELECT tags.id
        // FROM tags
        // LEFT JOIN resources_tags
        //   ON (tags.id = resources_tags.tag_id)
        //   WHERE resources_tags.tag_id IS NULL
        $query = $this->query();
        $query->select(['id', 'slug'])
            ->leftJoinWith('ResourcesTags')
            ->where(function ($exp, $q) {
                return $exp->isNull('ResourcesTags.tag_id');
            });

        $tags = Hash::extract($query->toArray(), '{n}.id');
        if (count($tags)) {
            return $this->deleteAll(['id IN' => $tags]);
        }

        return 0;
    }
}
