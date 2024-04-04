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
 */
namespace Passbolt\Log\Service\EntitiesHistory;

use App\Utility\UserAction;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Passbolt\Log\Model\Entity\EntityHistory;

class EntitiesHistoryCreateService
{
    /**
     * @var array
     */
    private $config = [
        'Share.share' => [
            'models' => [
                'Permissions' => [
                    EntityHistory::CRUD_CREATE,
                    EntityHistory::CRUD_UPDATE,
                    EntityHistory::CRUD_DELETE,
                ],
                'Secrets' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'FoldersShare.share' => [
            'models' => [
                'Permissions' => [
                    EntityHistory::CRUD_CREATE,
                    EntityHistory::CRUD_UPDATE,
                    EntityHistory::CRUD_DELETE,
                ],
                'FoldersRelations' => [
                    EntityHistory::CRUD_CREATE,
                    EntityHistory::CRUD_UPDATE,
                    EntityHistory::CRUD_DELETE,
                ],
            ],
        ],
        'FoldersCreate.create' => [
            'models' => [
                'Folders' => [
                    EntityHistory::CRUD_CREATE,
                ],
                'FoldersRelations' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'FoldersRelationsMove.move' => [
            'models' => [
                'FoldersRelations' => [
                    EntityHistory::CRUD_CREATE,
                    EntityHistory::CRUD_UPDATE,
                    EntityHistory::CRUD_DELETE,
                ],
            ],
        ],
        'FoldersUpdate.update' => [
            'models' => [
                'Folders' => [
                    EntityHistory::CRUD_UPDATE,
                ],
            ],
        ],
        'FoldersDelete.delete' => [
            'models' => [
                'Folders' => [
                    EntityHistory::CRUD_DELETE,
                ],
                'FoldersRelations' => [
                    EntityHistory::CRUD_DELETE,
                    EntityHistory::CRUD_CREATE,
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
        'UsersRegister.registerPost' => [
            'models' => [
                'Users' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'UsersAdd.addPost' => [
            'models' => [
                'Users' => [
                    EntityHistory::CRUD_CREATE,
                ],
            ],
        ],
        'UsersEdit.editPost' => [
            'models' => [
                'Users' => [
                    EntityHistory::CRUD_UPDATE,
                ],
            ],
        ],
        'UsersDelete.delete' => [
            'models' => [
                'Users' => [
                    EntityHistory::CRUD_DELETE,
                ],
            ],
        ],
    ];

    /**
     * Log entity history
     *
     * @param \Cake\Event\Event $event event
     * @return void
     */
    public function logEntityHistory(Event $event)
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
                // We first create the detailed history (PermissionsHistory, FoldersHistory, etc..)
                $foreignModel = $modelDetailedHistory;
                $detailedHistory = $table->getAssociation($foreignModel)
                      ->create($entity->toArray());

                // There can be manipulations of id while creating detailed history.
                // We make sure we have the id of the entity that has been created.
                $entityHistoryData['foreign_key'] = $detailedHistory->id;

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
     *
     * @param \Cake\ORM\Table $table table
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
     *
     * @param \Cake\Event\Event $event the event
     * @return string CRUD type.
     */
    private function _getCrudType(Event $event)
    {
        $entity = $event->getData('entity');
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
     *
     * @param \Cake\ORM\Entity $entity entity
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
     *
     * @param \Cake\Event\Event $event event
     * @param string $actionName action name
     * @return bool
     */
    private function isLogOperationNeeded(Event $event, string $actionName): bool
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
     *
     * @param string $actionName action name
     * @return array|null
     */
    private function getEntitiesHistoryConfig(string $actionName): ?array
    {
        if (isset($this->config[$actionName])) {
            return $this->config[$actionName];
        }

        return null;
    }
}
