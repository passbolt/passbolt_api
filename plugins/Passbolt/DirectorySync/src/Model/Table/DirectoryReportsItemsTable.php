<?php
namespace Passbolt\DirectorySync\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;

/**
 * DirectoryReportsItems Model
 *
 * @property \Passbolt\DirectorySync\Model\Table\ReportsTable|\Cake\ORM\Association\BelongsTo $Reports
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem newEntity($data = null, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryReportsItemsTable extends Table
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('status')
            ->maxLength('status', 36)
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->scalar('model')
            ->maxLength('model', 36)
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->scalar('action')
            ->maxLength('action', 36)
            ->requirePresence('action', 'create')
            ->notEmpty('action');

        $validator
            ->scalar('data')
            ->allowEmpty('data');

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
     * Create report item table.
     * @param string|null $reportId report id
     * @param ActionReport $reportItem report item
     * @return bool|\Passbolt\DirectorySync\Model\Entity\DirectoryReportsItem
     */
    public function create(string $reportId = null, ActionReport $reportItem)
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
