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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

/**
 * DirectoryEntries Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Groups
 *
 * @method \App\Model\Entity\DirectoryEntry get($primaryKey, $options = [])
 * @method \App\Model\Entity\DirectoryEntry newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DirectoryEntry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DirectoryEntry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DirectoryEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DirectoryEntry[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DirectoryEntry findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryEntriesTable extends Table
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

        $this->setTable('directory_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'className' => 'Users',
            'bindingKey' => 'foreign_key',
            'foreignKey' => 'id'
        ]);

        $this->hasOne('Groups', [
            'className' => 'Groups',
            'bindingKey' => 'foreign_key',
            'foreignKey' => 'id'
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
            ->scalar('id')
            ->allowEmpty('id', 'create');

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
