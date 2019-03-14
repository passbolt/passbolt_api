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

use App\Error\Exception\CustomValidationException;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\Table;
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
     * Retrieve all the tags by slugs.
     * @param array $slugs The slugs to search
     * @return Query
     *
     */
    public function findAllBySlugs(array $slugs = [])
    {
        return $this->find()
            ->where(['slug IN' => $slugs]);
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
        if (isset($options['contain']['all_tags'])) {
            $query->contain('Tags', function (Query $q) use ($userId) {
                return $q->order(['slug']);
            });
        }

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
    /**
     * Build tag entities or fail
     *
     * @param string $userId uuid owner of the tags
     * @param array $tags list of tag slugs
     * @throws BadRequestException if the validation fails
     * @return array $tags list of tag entities
     */
    public function buildEntitiesOrFail(string $userId = null, array $tags)
    {
        $collection = [];
        if (!empty($tags)) {
            foreach ($tags as $i => $slug) {
                $collection[$i] = $this->newEntity([
                    'slug' => $slug,
                ], [
                    'accessibleFields' => [
                        'slug' => true,
                        'is_shared' => true,
                        'resources_tags' => true
                    ]
                ]);
                // If not shared, add the user_id in the resources_tags join table
                $notShared = @mb_substr($slug, 0, 1, 'utf-8') !== '#';
                $resourceTagUserId = $notShared ? $userId : null;
                $collection[$i]['_joinData'] = $this->ResourcesTags->newEntity([
                    'user_id' => $resourceTagUserId
                ], [
                    'accessibleFields' => [
                        'user_id' => true
                    ]
                ]);
            }
        }
        $errors = [];
        foreach ($collection as $i => $tag) {
            if ($tag->getErrors()) {
                $errors[$i] = $tag->getErrors();
            }
        }
        if (!empty($errors)) {
            throw new CustomValidationException(__('Could not validate the tags.'), $errors);
        }

        return $collection;
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
