<?php
namespace Passbolt\DirectorySync\Model\Table;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Model\Entity\DirectoryReport;

/**
 * DirectoryReports Model
 *
 * @property \Passbolt\DirectorySync\Model\Table\DirectoryReportsTable|\Cake\ORM\Association\BelongsTo $ParentDirectoryReports
 * @property \Passbolt\DirectorySync\Model\Table\DirectoryReportsTable|\Cake\ORM\Association\HasMany $ChildDirectoryReports
 * @property \Passbolt\DirectorySync\Model\Table\DirectoryReportsTable|\Cake\ORM\Association\HasMany $DirectoryReportsItems
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport get($primaryKey, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport newEntity($data = null, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport[] newEntities(array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DirectoryReportsTable extends Table
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

        $this->setTable('directory_reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentDirectoryReports', [
            'className' => 'Passbolt/DirectorySync.DirectoryReports',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildDirectoryReports', [
            'className' => 'Passbolt/DirectorySync.DirectoryReports',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('DirectoryReportsItems', [
            'className' => 'Passbolt/DirectorySync.DirectoryReportsItems',
            'foreignKey' => 'parent_id',
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
        $rules->add(
            function ($entity, $options) {
                if ($entity->parent_id !== null) {
                    $DirectoryReport = TableRegistry::getTableLocator()->get('DirectoryReports');
                    try {
                        $DirectoryReport->get($entity->parent_id);
                    } catch (RecordNotFoundException $exception) {
                        return false;
                    }
                }

                return true;
            },
            'ParentDirectoryReports',
            [
                'errorField' => 'parent_id',
                'message' => __('The associated record could not be found'),
            ]
        );

        return $rules;
    }

    /**
     * @param string $parentId UUID parent report id
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryReport
     */
    public function create(string $parentId = null)
    {
        $entity = $this->newEntity([
            'parent_id' => $parentId,
            'status' => DirectoryReport::STATUS_RUNNING,
        ], [
            'accessibleFields' => [
                'parent_id' => true,
                'status' => true,
            ],
        ]);
        $result = $this->save($entity);

        return $result;
    }
}
