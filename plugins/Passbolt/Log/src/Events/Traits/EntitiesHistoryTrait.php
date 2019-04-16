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
 */
namespace Passbolt\Log\Events\Traits;

use App\Utility\UserAction;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Passbolt\Log\Model\Entity\EntityHistory;

trait EntitiesHistoryTrait
{
    private $config = [
        'Share.share' => [
            'models' => [
                'Permissions' => [
                    EntityHistory::CRUD_CREATE,
                    EntityHistory::CRUD_UPDATE,
                    EntityHistory::CRUD_DELETE
                ],
                'models' => [
                    'SecretAccesses' => [
                        EntityHistory::CRUD_CREATE,
                    ],
                ],
            ],
        ],
        'ResourcesAdd.add' => [
            'models' => [
                'Resources' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'ResourcesView.view' => [
            'models' => [
                'SecretAccesses' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'ResourcesIndex.index' => [
            'models' => [
                'SecretAccesses' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'ResourcesUpdate.update' => [
            'models' => [
                'Resources' => [
                    EntityHistory::CRUD_UPDATE,
                ],
                'Secrets' => [
                    EntityHistory::CRUD_UPDATE,
                ],
            ],
        ],
        'ResourcesDelete.delete' => [
            'models' => [
                'Resources' => [
                    EntityHistory::CRUD_DELETE,
                ],
            ],
        ],
        'SecretsView.view' => [
            'models' => [
                'SecretAccesses' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
    ];

    /**
     * Log entity history.
     * @param Event $event the event
     * @return void
     */
    public function logEntityHistory(Event $event)
    {
        if ($event->getName() == 'Model.afterSave' ||
            $event->getName() == 'Model.afterDelete' ||
            $event->getName() == 'Model.afterRead') {
            $this->_logEntityHistory($event);
        }
    }

    /**
     * Entity associations initialize
     * Initialize needed associations for the required models on the fly.
     * Example: we need to associate PermissionsHistory to Permissions in order to track the history.
     * @param Event $event the event
     * @return void
     */
    public function entityAssociationsInitialize(Event $event)
    {
        $table = $event->getSubject();
        $modelName = $table->getAlias();

        if ($modelName == 'Permissions') {
            $table->belongsTo('Passbolt/Log.PermissionsHistory', [
                'foreignKey' => 'foreign_key'
            ]);
        }
        if ($modelName == 'Resources') {
            $table->belongsTo('Passbolt/Log.EntitiesHistory', [
                'foreignKey' => 'foreign_key'
            ]);
        }
        if ($modelName == 'Secrets') {
            $table->belongsTo('Passbolt/Log.SecretsHistory', [
                'foreignKey' => 'foreign_key'
            ]);
            $table->hasMany('Passbolt/Log.SecretAccesses');
        }
        if ($modelName == 'SecretAccesses') {
            $table->belongsTo('Passbolt/Log.EntitiesHistory', [
                'foreignKey' => 'foreign_key'
            ]);
        }
    }

    /**
     * Log entity history
     * @param Event $event event
     * @return void
     */
    private function _logEntityHistory(Event $event)
    {
        $table = $event->getSubject();
        $modelName = $table->getAlias();
        try {
            $userAction = UserAction::getInstance();
        } catch (\Exception $exception) {
            // preventing the app to fail if no user action is set
            // we consider the log operation is not needed
            return;
        }

        if ($this->isLogOperationNeeded($event, $userAction->getActionName())) {
            $entity = $event->getData()['entity'];
            $crud = $this->_getCrudType($event);
            $foreignModel = $modelName;

            // EntityHistory data.
            $entityHistoryData = [
                'foreign_model' => $foreignModel,
                'foreign_key' => $entity['id'],
                'crud' => $crud,
            ];

            // If there is a detailed history table, populate it first, then entitiesHistory.
            $modelDetailedHistory = $this->_hasTableDetailedHistory($table);
            if ($modelDetailedHistory) {
                $foreignModel = $modelDetailedHistory;
                $table->getAssociation($foreignModel)
                      ->create($entity->toArray());

                $entityHistoryData['foreign_model'] = $foreignModel;
                $table->getAssociation($foreignModel)
                      ->getAssociation('EntitiesHistory')
                      ->create($entityHistoryData, $userAction);
            } else {
                // Else we populate directly entitiesHistory.
                $table->getAssociation('EntitiesHistory')
                      ->create($entityHistoryData, $userAction);
            }
        }
    }

    /**
     * Check if a table has a detailed history.
     * @param Table $table table
     *
     * @return bool|string
     */
    private function _hasTableDetailedHistory(Table $table)
    {
        $modelName = $table->getAlias();
        $detailedHistoryTableName = $modelName . 'History';
        if ($table->hasAssociation($detailedHistoryTableName)) {
            return $detailedHistoryTableName;
        }

        return false;
    }

    /**
     * Identify crud operation type based on the event.
     * @param Event $event the event
     *
     * @return string CRUD type.
     */
    private function _getCrudType(Event $event)
    {
        $entity = $event->getData()['entity'];
        $crud = EntityHistory::CRUD_CREATE;

        if ($event->getName() == 'Model.afterSave' && !$entity->isNew()) {
            if ($this->_isEntitySoftDeleted($entity)) {
                $crud = EntityHistory::CRUD_DELETE;
            } else {
                $crud = EntityHistory::CRUD_UPDATE;
            }
        } elseif ($event->getName() == 'Model.afterDelete') {
            $crud = EntityHistory::CRUD_DELETE;
        } elseif ($event->getName() == 'Model.afterRead') {
            $crud = EntityHistory::CRUD_READ;
        }

        return $crud;
    }

    /**
     * Check if an entity is soft deleted.
     * @param Entity $entity entity
     *
     * @return bool
     */
    private function _isEntitySoftDeleted(Entity $entity)
    {
        $dirtyFields = $entity->getDirty();
        $deletedIsTrue = isset($entity->deleted) && $entity->deleted == true;
        $deletedIsDirty = in_array('deleted', $dirtyFields);

        return $deletedIsTrue && $deletedIsDirty;
    }

    /**
     * Check if a log operation is needed based on the config.
     * @param Event $event event
     * @param string $actionName action name
     *
     * @return bool
     */
    private function isLogOperationNeeded(Event $event, string $actionName)
    {
        $config = $this->getEntitiesHistoryConfig($actionName);
        $table = $event->getSubject();
        $modelName = $table->getAlias();

        if ($config == null) {
            return false;
        }

        if (isset($config['models'][$modelName])) {
            $crud = $this->_getCrudType($event);

            return in_array($crud, $config['models'][$modelName]);
        }

        return false;
    }

    /**
     * Get entities history config
     * @param string $actionName action name
     *
     * @return |null
     */
    public function getEntitiesHistoryConfig(string $actionName)
    {
        if (isset($this->config[$actionName])) {
            return $this->config[$actionName];
        }

        return null;
    }
}
