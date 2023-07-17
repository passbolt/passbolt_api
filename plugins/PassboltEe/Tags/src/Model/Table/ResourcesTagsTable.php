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

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResourcesTags Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \Cake\ORM\Table&\Cake\ORM\Association\BelongsTo $Tags
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag get($primaryKey, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag newEntity(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag newEmptyEntity()
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ResourcesTagsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('resources_tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Tags', [
            'className' => 'Passbolt/Tags.Tags',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users');
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
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id');

        $validator
            ->uuid('resource_id', __('The resource identifier should be a valid UUID.'))
            ->notEmptyString('resource_id', __('The resource identifier should not be empty.'));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn(['resource_id'], 'Resources'));
        $rules->add($rules->existsIn(['tag_id'], 'Tags'));

        // If tag is shared user must be empty
        // If tag is personal user must not be empty
        // If user not empty it must exist
        // $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Update user tag
     *
     * @param string $userId User whose tags to update
     * @param string $oldTagId Id of the tag that needs update
     * @param string $newTagId New tag id
     * @return void
     */
    public function updateUserTag(string $userId, string $oldTagId, string $newTagId)
    {
        // Find all the user resources already tagged with the new tag.
        $resourcesIdAssociatedWithNewTag = $this->find()
            ->select(['resource_id'])
            ->where([
                'tag_id' => $newTagId,
                'user_id' => $userId,
            ])
            ->all()
            ->extract('resource_id')
            ->toArray();

        // Tag with the new tag only the user resources that haven't been yet tagged with the new tag.
        $updateWhere = [
            'tag_id' => $oldTagId,
            'user_id' => $userId,
        ];
        if (!empty($resourcesIdAssociatedWithNewTag)) {
            $updateWhere['resource_id NOT IN'] = $resourcesIdAssociatedWithNewTag;
        }
        $this->updateAll([
            'tag_id' => $newTagId,
        ], $updateWhere);

        // Remove all associations between the old tag and the user resources.
        $this->deleteAll([
            'tag_id' => $oldTagId,
            'user_id' => $userId,
        ]);
    }
}
