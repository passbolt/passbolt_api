<?php
declare(strict_types=1);

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
use Passbolt\DirectorySync\Actions\Reports\ActionReport;

/**
 * DirectoryReportsItems Model
 *
 * @property \Cake\ORM\Table&\Cake\ORM\Association\BelongsTo $Reports
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem newEntity(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem newEmptyEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DirectoryReportsItemsTable extends Table
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

        $this->setTable('directory_reports_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Reports', [
            'foreignKey' => 'report_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/DirectorySync.Reports',
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
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('status')
            ->maxLength('status', 36)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->scalar('model')
            ->maxLength('model', 36)
            ->requirePresence('model', 'create')
            ->notEmptyString('model');

        $validator
            ->scalar('action')
            ->maxLength('action', 36)
            ->requirePresence('action', 'create')
            ->notEmptyString('action');

        $validator
            ->scalar('data')
            ->notEmptyString('data');

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
        return $rules;
    }

    /**
     * Create report item table.
     *
     * @param string|null $reportId report id
     * @param \Passbolt\DirectorySync\Actions\Reports\ActionReport $reportItem report item
     * @return bool|\Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem
     */
    public function create(?string $reportId, ActionReport $reportItem)
    {
        $entity = $this->newEntity([
            'report_id' => $reportId,
            'status' => $reportItem->getStatus(),
            'model' => $reportItem->getModel(),
            'action' => $reportItem->getStatus(),
            'data' => serialize($reportItem->getData()),
        ], [
            'accessibleFields' => [
                'report_id' => true,
                'status' => true,
                'model' => true,
                'action' => true,
                'data' => true,
            ],
        ]);
        $result = $this->save($entity);

        return $result;
    }
}
