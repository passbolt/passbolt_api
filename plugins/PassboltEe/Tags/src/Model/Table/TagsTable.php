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
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use App\ORM\Association\PassboltBelongsToMany;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Rule\IsMetadataKeyTypeAllowedBySettingsRule;
use Passbolt\Metadata\Model\Rule\IsV4ToV5UpgradeAllowedRule;
use Passbolt\Metadata\Model\Rule\IsValidEncryptedMetadataRule;
use Passbolt\Metadata\Model\Rule\MetadataKeyIdExistsInRule;
use Passbolt\Metadata\Model\Rule\MetadataKeyIdNotExpiredRule;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\Metadata\MetadataTagsRenderService;

/**
 * Tags Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsToMany $Resources
 * @property \Passbolt\Tags\Model\Table\ResourcesTagsTable&\Cake\ORM\Association\HasMany $ResourcesTags
 * @method \Passbolt\Tags\Model\Entity\Tag get($primaryKey, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag newEntity(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Tags\Model\Entity\Tag newEmptyEntity()
 * @method \Passbolt\Tags\Model\Entity\Tag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Tags\Model\Entity\Tag>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Tags\Model\Entity\Tag>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Tags\Model\Entity\Tag>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Tags\Model\Entity\Tag>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findBySlug(string $slug)
 */
class TagsTable extends Table
{
    use CaseSensitiveCompareValueTrait;
    use TagsTableBackupAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTableNameBackupModeAware('tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Resources', [
            'through' => 'ResourcesTags',
        ]);

        $this->hasMany('ResourcesTags');

        $this->belongsToMany('Users', [
            'through' => 'ResourcesTags',
        ]);
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
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationV5(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        // Remove all validation on the v4 meta properties
        // Enforce all v4 fields to be empty
        foreach (MetadataTagDto::V4_META_PROPS as $v4Fields) {
            $validator->remove($v4Fields);
        }

        /**
         * V5 fields validations.
         */
        $validator
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'))
            ->allowEmptyString('metadata_key_id');

        $validator
            ->ascii('metadata', __('The metadata should be a valid ASCII string.'))
            ->requirePresence('metadata', 'create', __('An armored key is required.'))
            ->notEmptyString('metadata', __('The metadata should not be empty.'))
            ->add('metadata', 'isMetadataParsable', new IsParsableMessageValidationRule());

        $validator
            ->utf8Extended('metadata_key_type', __('The metadata key type should be a valid UTF8 string.'))
            ->allowEmptyString('metadata_key_type')
            ->inList('metadata_key_type', ['user_key', 'shared_key'], __(
                'The metadata key type should be one of the following: {0}.',
                implode(', ', ['user_key', 'shared_key'])
            ));

        return $validator;
    }

    /**
     * Rule checker for v5 properties
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRulesV5(RulesChecker $rules): RulesChecker
    {
        $rules->add(new IsMetadataKeyTypeAllowedBySettingsRule(), 'isMetadataKeyTypeAllowedBySettings', [
            'errorField' => 'metadata_key_type',
            'message' => __('The settings selected by your administrator prevent from using that key type.'),
        ]);

        $rules->add(new MetadataKeyIdExistsInRule(), 'metadata_key_exists', [
            'errorField' => 'metadata_key_id',
            'message' => __('The metadata key does not exist.'),
        ]);

        $rules->add(new MetadataKeyIdNotExpiredRule(), 'isMetadataKeyNotExpired', [
            'errorField' => 'metadata_key_id',
            'message' => __('The metadata key is marked as expired.'),
        ]);

        $rules->add(new IsValidEncryptedMetadataRule(), 'isValidEncryptedMetadata', [
            'errorField' => 'metadata',
            'message' => __('The tag metadata OpenPGP message cannot be parsed or is not for the intended recipient.'), // phpcs:ignore
        ]);

        $rules->addUpdate(new IsV4ToV5UpgradeAllowedRule(), 'v4_to_v5_upgrade_allowed', [
            'errorField' => 'metadata',
            'message' => __('The settings selected by your administrator prevent from upgrading v4 to v5.'),
        ]);

        return $rules;
    }

    /**
     * @param \Cake\Event\EventInterface $event Event object.
     * @param \Cake\Datasource\EntityInterface $entity Entity object.
     * @param \ArrayObject $options Options array.
     * @param string $operation Create/update operation.
     * @return true|void Return result when event is stopped, void otherwise.
     */
    public function beforeRules(
        EventInterface $event,
        EntityInterface $entity,
        \ArrayObject $options,
        string $operation
    ) {
        $dto = MetadataTagDto::fromArray($entity->toArray());

        if (!$dto->isV5()) {
            // This is little hack to not call `buildRulesV5` rules,
            // Because these are saved as belongsToMany association along with resources, it tries to call build rules even for V4 tags.
            $event->stopPropagation();

            return true;
        }
    }

    /**
     * Find tags for index
     *
     * @param string $userId uuid of the user to find tags for.
     * @return array|\Cake\ORM\Query
     */
    public function findIndex(string $userId)
    {
        $query = $this->find()
             ->orderBy($this->aliasField('slug'))
             ->distinct();

        // We here rebuild the associations manually to help CakePHP
        // The filtering by permissions should be done after the
        // joins on resources is written in the query
         $query
             ->innerJoin(['ResourcesTags' => 'resources_tags'], [
                 'ResourcesTags.tag_id' => new IdentifierExpression('Tags.id'),
             ])
             ->innerJoin(['Resources' => 'resources'], [
                'Resources.id' => new IdentifierExpression('ResourcesTags.resource_id'),
                 'Resources.deleted <' => 1,
             ])
             ->where([
                 'OR' => [
                     'ResourcesTags.user_id' => $userId,
                     $query->newExpr()->isNull('ResourcesTags.user_id'),
                 ],
             ]);
         $this->Resources->filterResourcesByPermissions($query, $userId);

        return $query;
    }

    /**
     * Retrieve all the tags by slugs.
     *
     * @param array|null $slugs The slugs to search
     * @param array $encryptedTagsIds The tag identifiers of encrypted tags (V5)
     * @return \Cake\ORM\Query
     * @throws \Cake\Database\Exception\DatabaseException if $slugs is empty
     */
    public function findAllBySlugsOrIds(?array $slugs = [], array $encryptedTagsIds = [])
    {
        $query = $this->find();

        if (!empty($slugs) && !empty($encryptedTagsIds)) {
            $query->where([
                'OR' => [
                    ['slug IN' => $this->getCaseSensitiveValues($query, $slugs)],
                    ['id IN' => $encryptedTagsIds],
                ],
            ]);
        } elseif (!empty($slugs)) {
            $query->where(['slug IN' => $this->getCaseSensitiveValues($query, $slugs)]);
        } else {
            $query->where(['id IN' => $encryptedTagsIds]);
        }

        return $query;
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
    public function decorateForeignFind(Query $query, array $options, string $userId): Query
    {
        if (isset($options['contain']['all_tags'])) {
            $query->contain('Tags', function (Query $q) {
                return $q->orderBy(['slug']);
            });
        } elseif (isset($options['contain']['tag'])) {
            // Display the user tags for a given resource
            $query
                ->contain('Tags', function (Query $q) use ($userId) {
                    return $q
                        ->orderBy(['slug'])
                        ->where(function (QueryExpression $where) use ($userId) {
                            return $where->or(function (QueryExpression $or) use ($userId) {
                                return $or
                                    ->eq('ResourcesTags.user_id', $userId)
                                    ->isNull('ResourcesTags.user_id');
                            });
                        })
                        // Return fields according to v4/v5 format
                        ->formatResults(function (CollectionInterface $tags) {
                            return $tags->map(function ($tag) {
                                $tag = is_object($tag) ? $tag->toArray() : $tag;

                                try {
                                    $tagDto = MetadataTagDto::fromArray($tag);
                                    $isV5 = $tagDto->isV5();
                                } catch (\Exception $e) {
                                    if (Configure::read('debug')) {
                                        $msg = __('Error while building tag DTO.');
                                        Log::error($msg . ' ' . $e->getMessage());
                                    }

                                    $isV5 = false;
                                }

                                return (new MetadataTagsRenderService())->renderTag($tag, $isV5);
                            });
                        });
                })
                // Exclude _joinData from the result set. This strategy performs better than formatResults or map.
                ->applyOptions([PassboltBelongsToMany::QUERY_OPTION_EXCLUDE_JUNCTION_PROPERTY => true]);
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
     * @param string|null $userId uuid owner of the tags
     * @param array $tags list of tag slugs
     * @throws \Cake\Http\Exception\BadRequestException if the validation fails
     * @return array $tags list of tag entities
     */
    public function buildEntitiesOrFail(?string $userId, array $tags)
    {
        if (empty($tags)) {
            return [];
        }

        $collection = [];
        $errors = [];

        foreach ($tags as $i => $tag) {
            if (is_string($tag)) {
                $tag = ['slug' => $tag];
            }

            $dto = MetadataTagDto::fromArray($tag);

            try {
                $collection[$i] = $this->buildEntityOrFail($dto);

                // If not shared, add the user_id in the resources_tags join table
                $resourceTagUserId = null;
                if ($dto->isPersonal()) {
                    $resourceTagUserId = $userId;
                }
                $collection[$i]['_joinData'] = $this->ResourcesTags->newEntity(
                    ['user_id' => $resourceTagUserId],
                    ['accessibleFields' => ['user_id' => true]]
                );
            } catch (CustomValidationException $e) {
                $errors[$i] = $e->getErrors();
            }
        }

        if (!empty($errors)) {
            throw new CustomValidationException(__('Could not validate tags data.'), $errors);
        }

        return $collection;
    }

    /**
     * @param \Passbolt\Tags\Model\Dto\MetadataTagDto $dto DTO.
     * @return \Passbolt\Tags\Model\Entity\Tag
     * @throws \App\Error\Exception\CustomValidationException When there are errors building entity object.
     */
    public function buildEntityOrFail(MetadataTagDto $dto)
    {
        $tag = $dto->toArray();

        if ($dto->isV5()) {
            $data = [
                'metadata' => $tag['metadata'],
                'metadata_key_id' => $tag['metadata_key_id'],
                'metadata_key_type' => $tag['metadata_key_type'],
                'is_shared' => $tag['is_shared'],
            ];
            $options = [
                'accessibleFields' => [
                    'metadata' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'is_shared' => true,
                    'resources_tags' => true,
                ],
                'validate' => 'v5',
            ];
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $this->rulesChecker();
            $this->buildRulesV5($rules);
        } else {
            $data = ['slug' => $tag['slug']];
            $options = [
                'accessibleFields' => [
                    'slug' => true,
                    'is_shared' => true,
                    'resources_tags' => true,
                ],
            ];
        }

        $entity = $this->newEntity($data, $options);

        if (!empty($entity->getErrors())) {
            throw new CustomValidationException(__('Unable to build tag entity'), $entity->getErrors());
        }

        return $entity;
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
        $query = $this->selectQuery();
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
     * @throws \App\Error\Exception\CustomValidationException When validation errors.
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
