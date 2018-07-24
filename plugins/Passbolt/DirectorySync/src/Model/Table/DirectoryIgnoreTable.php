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

use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DirectoryIgnore Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasOne $Groups
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore newEntity($data = null, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryIgnoreTable extends Table
{
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('directory_ignore');
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
            ->scalar('id')
            ->uuid('id')
            ->requirePresence('id');

        $validator
            ->scalar('foreign_model')
            ->requirePresence('foreign_model');

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

    /**
     * @param $data
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryIgnore|bool
     */
    public function create($data)
    {
        $entity = $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true
            ]
        ]);
        return $this->save($entity);
    }
}
