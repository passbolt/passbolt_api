<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\Tags\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Model\Traits\Query\CaseSensitiveCompareValueTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Tags Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsToMany $Resources
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $ResourcesTags
 * @method \Passbolt\Tags\Model\Entity\Tag get($primaryKey, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag newEntity(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag newEmptyEntity()
 * @method \Passbolt\Tags\Model\Entity\Tag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findBySlug(string $slug)
 */
class TagsTable extends Table
{
    use CaseSensitiveCompareValueTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Resources', [
            'through' => 'ResourcesTags',
        ]);

        $this->hasMany('ResourcesTags');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->notEmptyString('slug', __('The tag should not be empty.'))
            ->requirePresence('slug', 'create', __('A tag is required.'))
            ->utf8Extended('slug', __('The tag should be a valid BMP-UTF8 string.'))
            ->maxLength('slug', 128, __('The tag length should be maximum {0} characters.', 128));

        $validator
            ->boolean('is_shared', __('The shared status should be a valid boolean.'))
            ->requirePresence('is_shared', 'create', __('A shared status is required.'));

        return $validator;
    }

    /**
     * Find tags for index
     *
     * @param string $userId uuid of the user to find tags for.
     * @return array|\Cake\ORM\Query
     */
    public function findIndex(string $userId)
    {
        $resources = $this->Resources->findIndex($userId);
        $resourcesId = Hash::extract($resources->toArray(), '{n}.id');

        if (!empty($resourcesId)) {
            return $this->find()
                ->innerJoinWith('ResourcesTags', function (Query $q) use ($resourcesId, $userId) {
                    return $q->where([
                        'ResourcesTags.resource_id IN' => $resourcesId,
                        'OR' => [
                            'ResourcesTags.user_id' => $userId,
                            $q->newExpr()->isNull('ResourcesTags.user_id'),
                        ],
                    ]);
                })
                ->order($this->aliasField('slug'))
                ->distinct();
        }

        return [];
    }

    /**
     * Retrieve all the tags by slugs.
     *
     * @param array|null $slugs The slugs to search
     * @return \Cake\ORM\Query
     * @throws \Cake\Database\Exception\DatabaseException if $slugs is empty
     */
    public function findAllBySlugs(?array $slugs = [])
    {
        $query = $this->find();

        return $query->where(['slug IN' => $this->getCaseSensitiveValues($query, $slugs)]);
    }

    /**
     * Decorate a query with the necessary tag finder conditions
     * Usefull to do a contain[tag] and filter[has-tags] on resources for example
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $options Options
     * @param string $userId The user identifier to decorate for
     * @return \Cake\ORM\Query
     */
    public static function decorateForeignFind(Query $query, array $options, string $userId)
    {
        if (isset($options['contain']['all_tags'])) {
            $query->contain('Tags', function (Query $q) {
                return $q->order(['slug']);
            });
        }

        // Display the user tags for a given resource
        if (isset($options['contain']['tag'])) {
            $query->contain('Tags', function (Query $q) use ($userId) {
                return $q
                    ->order(['slug'])
                    ->where(function (QueryExpression $where) use ($userId) {
                        return $where->or(function (QueryExpression $or) use ($userId) {
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
     * @param string|null $userId uuid owner of the tags
     * @param array $tags list of tag slugs
     * @throws \Cake\Http\Exception\BadRequestException if the validation fails
     * @return array $tags list of tag entities
     */
    public function buildEntitiesOrFail(?string $userId, array $tags)
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
                        'resources_tags' => true,
                    ],
                ]);
                if ($collection[$i]->getErrors()) {
                    continue;
                }
                // If not shared, add the user_id in the resources_tags join table
                // @codingStandardsIgnoreStart
                $notShared = @mb_substr($slug, 0, 1, 'utf-8') !== '#';
                // @codingStandardsIgnoreEnd
                $resourceTagUserId = $notShared ? $userId : null;
                $collection[$i]['_joinData'] = $this->ResourcesTags->newEntity([
                    'user_id' => $resourceTagUserId,
                ], [
                    'accessibleFields' => [
                        'user_id' => true,
                    ],
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
            throw new CustomValidationException(__('Could not validate tags data.'), $errors);
        }

        return $collection;
    }

    /**
     * Delete all the unused tags if any
     *
     * @return int number of affected rows
     */
    public function deleteAllUnusedTags(): int
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

    /**
     * Find or create a tag with the given slug
     *
     * @param string $slug The slug to search for
     * @param \App\Utility\UserAccessControl $control User Access Control
     * @return mixed
     */
    public function findOrCreateTag(string $slug, UserAccessControl $control)
    {
        $query = $this->find();

        /** @var \Passbolt\Tags\Model\Entity\Tag|null $tagExists */
        $tagExists = $query->where(['slug' => $this->getCaseSensitiveValue($query, $slug)])->first();

        if (!$tagExists) {
            if (mb_substr($slug, 0, 1) === '#' && !$control->isAdmin()) {
                throw new ForbiddenException('You do not have the permission to create a shared tag.');
            }

            $isShared = mb_substr($slug, 0, 1) === '#';

            $tagExists = $this->newEntity([
                'slug' => $slug,
                'is_shared' => $isShared,
            ], [
                'accessibleFields' => [
                    'slug' => true,
                    'is_shared' => true,
                ],
            ]);

            if (!empty($tagExists->getErrors())) {
                throw new CustomValidationException('Could not validate tag data.', $tagExists->getErrors());
            }

            $this->save($tagExists);
        }

        return $tagExists;
    }
}
