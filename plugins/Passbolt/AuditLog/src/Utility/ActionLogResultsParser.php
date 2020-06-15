<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Utility;

use App\Model\Table\AvatarsTable;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Model\Entity\EntityHistory;

class ActionLogResultsParser
{
    protected $actionLogs = [];
    protected $entries = [];
    protected $filters = [];

    const TYPE_PERMISSIONS_UPDATED = 'Permissions.updated';
    const TYPE_SECRETS_READ = 'Resource.Secrets.read';
    const TYPE_SECRETS_UPDATED = 'Resource.Secrets.updated';
    const TYPE_RESOURCE_CREATED = 'Resources.created';
    const TYPE_RESOURCE_UPDATED = 'Resources.updated';
    const TYPE_RESOURCE_DELETED = 'Resources.deleted';
    const TYPE_FOLDER_CREATED = 'Folders.created';
    const TYPE_FOLDER_UPDATED = 'Folders.updated';
    const TYPE_FOLDER_DELETED = 'Folders.deleted';

    /**
     * ActionLogResultsParser constructor.
     *
     * @param ResultSet $actionLogs action logs
     * @param array $filters list of filters
     *   - array resources is the one currently supported. It should contain a list of ids.
     * @return void
     */
    public function __construct(ResultSet $actionLogs, array $filters = [])
    {
        $this->actionLogs = $actionLogs;
        $this->filters = $filters;
    }

    /**
     * Parse action logs
     * @return array list of entries
     */
    public function parse()
    {
        foreach ($this->actionLogs as $actionLog) {
            $this->addEntries($actionLog);
        }

        return $this->getEntries();
    }

    /**
     * Add an entry in the entries list
     * @param string $type type
     * @param array $data data
     * @param ActionLog $actionLog actionLog object
     *
     * @return array corresponding entry
     */
    protected function _addEntry(string $type, array $data, ActionLog $actionLog)
    {
        $entry = [
            'action_log_id' => $actionLog->id,
            'type' => $type,
            'data' => $data,
            'creator' => isset($actionLog->user) ? $actionLog->user->toArray() : null,
            'created' => $actionLog->created,
        ];
        $entry['id'] = $this->getEntryId($entry);

        $this->entries[] = $entry;

        return $entry;
    }

    /**
     * Get unique id for a given entry.
     * One action log can produce several entries. It is necessary that we can identify each entry
     * with a unique id.
     * An entry id is a uuid made of action_log_id + its position in the list of entries for the given action log.s
     * @param array $entry entry
     *
     * @return string entry id
     */
    protected function getEntryId(array $entry)
    {
        $nbEntriesForActionLog = 0;

        foreach ($this->entries as $listEntry) {
            if ($listEntry['action_log_id'] === $entry['action_log_id']) {
                $nbEntriesForActionLog++;
            }
        }

        $id = UuidFactory::uuid($entry['action_log_id'] . $nbEntriesForActionLog);

        return $id;
    }

    /**
     * Process resources crud operations.
     * @param ActionLog $actionLog action log
     * @return void
     */
    protected function _processResourcesCrudOperations(ActionLog $actionLog)
    {
        foreach ($actionLog->entities_history as $entityHistory) {
            if ($entityHistory->foreign_model == 'Resources') {
                $data = [
                    'resource' => $entityHistory->resource->toArray(),
                ];

                if ($entityHistory->crud == EntityHistory::CRUD_CREATE) {
                    $type = self::TYPE_RESOURCE_CREATED;
                }

                if ($entityHistory->crud == EntityHistory::CRUD_UPDATE) {
                    $type = self::TYPE_RESOURCE_UPDATED;
                }

                if ($entityHistory->crud == EntityHistory::CRUD_DELETE) {
                    $type = self::TYPE_RESOURCE_DELETED;
                }

                if (isset($type)) {
                    $this->_addEntry($type, $data, $actionLog);
                }
            }
        }
    }

    /**
     * Process folders crud operations.
     * @param ActionLog $actionLog action log
     * @return void
     */
    protected function _processFoldersCrudOperations(ActionLog $actionLog)
    {
        foreach ($actionLog->entities_history as $entityHistory) {
            if ($entityHistory->foreign_model == 'FoldersHistory') {
                $data = [
                    'folder' => $entityHistory->folders_history->toArray(),
                ];

                if ($entityHistory->crud == EntityHistory::CRUD_CREATE) {
                    $type = self::TYPE_FOLDER_CREATED;
                }

                if ($entityHistory->crud == EntityHistory::CRUD_UPDATE) {
                    $type = self::TYPE_FOLDER_UPDATED;
                }

                if ($entityHistory->crud == EntityHistory::CRUD_DELETE) {
                    $type = self::TYPE_FOLDER_DELETED;
                }

                if (isset($type)) {
                    $this->_addEntry($type, $data, $actionLog);
                }
            }
        }
    }

    /**
     * Process secrets update operations
     * @param ActionLog $actionLog action log
     * @return void
     */
    protected function _processSecretsUpdateOperations(ActionLog $actionLog)
    {
        $secretUpdated = false;
        $data = [
            'resource' => null,
            'secrets' => [],
        ];

        foreach ($actionLog->entities_history as $entityHistory) {
            if ($entityHistory->foreign_model == 'SecretsHistory' && isset($entityHistory->secrets_history)) {
                $secretUpdated = true;
                if (!isset($data['resource'])) {
                    $data['resource'] = $entityHistory->secrets_history->secrets_history_resource;
                }
                $data['secrets'][] = $entityHistory->secrets_history->toArray();
            }
        }

        if ($secretUpdated) {
            $this->_addEntry(self::TYPE_SECRETS_UPDATED, $data, $actionLog);
        }
    }

    /**
     * Process secret accesses operations
     * @param ActionLog $actionLog action log
     * @return void
     */
    protected function _processSecretAccessesOperations(ActionLog $actionLog)
    {
        foreach ($actionLog->entities_history as $entityHistory) {
            if ($entityHistory->foreign_model == 'SecretAccesses') {
                // If the resources filter is set, and the current resource is not in the filter, we skip the entry.
                if (
                    !empty($this->filters)
                    && isset($this->filters['resources'])
                    && !in_array($entityHistory->secret_access->secret_access_resource->id, $this->filters['resources'])
                ) {
                    continue;
                }

                $data = [
                    'resource' => $entityHistory->secret_access->secret_access_resource->toArray(),
                ];

                if ($entityHistory->crud == EntityHistory::CRUD_CREATE) {
                    $this->_addEntry(self::TYPE_SECRETS_READ, $data, $actionLog);
                }
            }
        }
    }

    /**
     * Process permissions update operations
     * @param ActionLog $actionLog action log
     * @return void
     */
    protected function _processPermissionsUpdateOperations(ActionLog $actionLog)
    {
        $permissionsUpdated = false;
        $data = [
            'permissions' => [
                'added' => [],
                'updated' => [],
                'removed' => [],
            ],
        ];
        foreach ($actionLog->entities_history as $entityHistory) {
            if ($entityHistory->foreign_model === 'PermissionsHistory') {
                // Added permissions
                if ($entityHistory->crud == EntityHistory::CRUD_CREATE) {
                    $type = 'added';
                } elseif ($entityHistory->crud == EntityHistory::CRUD_DELETE) {
                    $type = 'removed';
                } elseif ($entityHistory->crud == EntityHistory::CRUD_UPDATE) {
                    $type = 'updated';
                }

                $permission = $entityHistory->permissions_history;
                $permission->resource = $permission->permissions_history_resource;
                unset($permission->permissions_history_resource);

                if (Configure::read('passbolt.plugins.folders.enabled')) {
                    if (isset($permission->permissions_history_folder)) {
                        $permission->folder = $permission->permissions_history_folder;
                        unset($permission->permissions_history_folder);
                    }
                }

                $permission->user = $permission->permissions_history_user;
                unset($permission->permissions_history_user);

                // Add profiles data for user.
                if (isset($permission->user)) {
                    $permission->user->profile = $this->_getUserObject($permission->user->id)->profile;
                }

                $permission->group = $permission->permissions_history_group;
                unset($permission->permissions_history_group);

                if (isset($type)) {
                    $permissionsUpdated = true;
                    $data['permissions'][$type][] = $entityHistory->permissions_history->toArray();
                }

                if (!isset($data['resource'])) {
                    $data['resource'] = $permission->resource;
                }

                if (Configure::read('passbolt.plugins.folders.enabled')) {
                    if (! isset($data['folder'])) {
                        $data['folder'] = $permission->folder;
                    }
                }
            }
        }
        if ($permissionsUpdated == true) {
            $this->_addEntry(self::TYPE_PERMISSIONS_UPDATED, $data, $actionLog);
        }
    }

    /**
     * Get the corresponding user object with its profile and avatar from a user id.
     * @param string $userId user id.
     *
     * @return array|\Cake\Datasource\EntityInterface|null user object
     */
    protected function _getUserObject(string $userId)
    {
        $User = TableRegistry::getTableLocator()->get('Users');
        $u = $User
            ->find()
            ->select(['Users.id', 'Users.username'])
            ->select($User->Profiles)
            ->select($User->Profiles->Avatars)
            ->where(['Users.id' => $userId])
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->first();

        return $u;
    }

    /**
     * Add entries corresponding to a given action log.
     * @param ActionLog $actionLog action log
     * @return void
     */
    public function addEntries(ActionLog $actionLog)
    {
        $this->_processResourcesCrudOperations($actionLog);
        $this->_processSecretAccessesOperations($actionLog);
        $this->_processSecretsUpdateOperations($actionLog);
        $this->_processPermissionsUpdateOperations($actionLog);
        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $this->_processFoldersCrudOperations($actionLog);
        }
    }

    /**
     * Get entries.
     * @return array entries
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
