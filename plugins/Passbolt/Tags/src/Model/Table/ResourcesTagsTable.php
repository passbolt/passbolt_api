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
 * @property \App\Model\Table\ResourcesTable|\Cake\ORM\Association\BelongsTo $Resources
 * @property \Passbolt\Tags\Model\Table\TagsTable|\Cake\ORM\Association\BelongsTo $Tags
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag get($primaryKey, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[] newEntities(array $data, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag|bool save(\Cake\Datasource\EntityInterface $entity, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag findOrCreate($search, callable $callback = null, ?array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourcesTagsTable extends Table
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

        $this->setTable('resources_tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Tags', [
            'foreignKey' => 'tag_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->uuid('user_id')
            ->allowEmpty('user_id');

        $validator
            ->uuid('resource_id')
            ->notEmpty('resource_id');

        $validator
            ->uuid('resource_id')
            ->notEmpty('resource_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
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
