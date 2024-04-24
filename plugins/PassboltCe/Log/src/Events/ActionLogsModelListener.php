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
namespace Passbolt\Log\Events;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Passbolt\Log\Service\EntitiesHistory\EntitiesHistoryCreateService;

class ActionLogsModelListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        /**
         * Return a list if implemented Events, with their callback.
         * The callback is based on the camelized name of the event slug.
         * Example: event "user.add" will have callback "logUserAdd"
         */
        return [
            'Model.afterSave' => 'logEntityHistory',
            'Model.afterDelete' => 'logEntityHistory',
            'Model.afterRead' => 'logEntityHistory',
            'Model.initialize' => 'entityAssociationsInitialize',
        ];
    }

    /**
     * Entity associations initialize
     * Initialize needed associations for the required core models on the fly.
     * Example: we need to associate PermissionsHistory to Permissions in order to track the history.
     *
     * @param \Cake\Event\Event $event the event
     * @return void
     */
    public function entityAssociationsInitialize(Event $event)
    {
        $table = $event->getSubject();
        $modelName = $table->getAlias();

        if ($modelName == 'Permissions') {
            $table->belongsTo('Passbolt/Log.PermissionsHistory', [
                'foreignKey' => 'foreign_key',
            ]);
        }
        if ($modelName == 'Resources') {
            $table->belongsTo('Passbolt/Log.EntitiesHistory', [
                'foreignKey' => 'foreign_key',
            ]);
        }
        if ($modelName == 'Secrets') {
            $table->belongsTo('Passbolt/Log.SecretsHistory', [
                'foreignKey' => 'foreign_key',
            ]);
            $table->hasMany('Passbolt/Log.SecretAccesses');
        }
        if ($modelName == 'SecretAccesses') {
            $table->belongsTo('Passbolt/Log.EntitiesHistory', [
                'foreignKey' => 'foreign_key',
            ]);
        }
        if (Configure::read('passbolt.plugins.folders.enabled')) {
            if ($modelName == 'Folders') {
                $table->belongsTo('FoldersHistory', [
                    'className' => 'Passbolt/Folders.FoldersHistory',
                    'foreignKey' => 'foreign_key',
                ]);
            }
            if ($modelName == 'FoldersRelations') {
                $table->belongsTo('FoldersRelationsHistory', [
                    'className' => 'Passbolt/Folders.FoldersRelationsHistory',
                    'foreignKey' => 'foreign_key',
                ]);
            }
        }
    }

    /**
     * Log entity history.
     *
     * @param \Cake\Event\Event $event the event
     * @return void
     */
    public function logEntityHistory(Event $event)
    {
        $entitiesHistoryService = new EntitiesHistoryCreateService();
        $entitiesHistoryService->logEntityHistory($event);
    }
}
