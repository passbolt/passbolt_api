<?php
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
 * @since         2.14.0
 */
namespace Passbolt\Folders\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoldersRelations Model
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation get($primaryKey, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEntity($data = null, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoldersRelationsTable extends Table
{
    /**
     * List of allowed item models on which a folder relation can be plugged.
     */
    const ALLOWED_FOREIGN_MODELS = [
        'Folder',
        'Resource'
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('folders_relations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __(
                'The foreign model must be one of the following: {0}.',
                implode(', ', self::ALLOWED_FOREIGN_MODELS)
            ))
            ->requirePresence('foreign_model', 'create', __('The foreign model is required.'))
            ->notEmptyString('foreign_model', __('The foreign model cannot be empty'));

        $validator
            ->uuid('foreign_id')
            ->requirePresence('foreign_id', 'create', __('The foreign id is required.'))
            ->notEmptyString('foreign_id', __('The foreign id cannot be empty.'), false);

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id', __('The user id cannot be empty.'), false);

        $validator
            ->uuid('folder_parent_id')
            ->allowEmptyString('folder_parent_id');

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
        return $rules;
    }
}
