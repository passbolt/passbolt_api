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
 * @since         5.7.0
 */
namespace App\Service\Secrets;

use App\Model\Table\ResourcesTable;
use App\Model\Table\SecretsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Table\EntitiesHistoryTable;

class PopulateCreatedByAndModifiedByInSecretsService
{
    private ResourcesTable $ResourcesTable;

    private EntitiesHistoryTable $EntitiesHistoryTable;

    private SecretsTable $SecretsTable;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->EntitiesHistoryTable = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $this->SecretsTable = TableRegistry::getTableLocator()->get('Secrets');
    }

    /**
     * @return void
     */
    public function populate(): void
    {
        $tbl = $this->SecretsTable->getTable();

        // Latest user responsible for creating or updating this secret (by secret id).
        $latestUserIdSub = $this->EntitiesHistoryTable->find()
            ->select(['ActionLogs.user_id'])
            // Join the action that originated the secret creation/update.
            ->innerJoin(['ActionLogs' => 'action_logs'], [
                'ActionLogs.id' => new IdentifierExpression('EntitiesHistory.action_log_id'),
                $this->EntitiesHistoryTable->find()->newExpr()->isNotNull('ActionLogs.user_id'),
            ])
            // Correlate with the outer UPDATE query  row: use the physical table name because CakePHP removes aliases in UPDATE/DELETE queries.
            ->where(['EntitiesHistory.foreign_key' => new IdentifierExpression("$tbl.id")])
            // Use the most recent relevant action.
            ->orderByDesc('EntitiesHistory.created')
            ->limit(1);

        // Fallback on modified_by from resources (by resource_id).
        $fallbackCreatedBySub = $this->ResourcesTable->find()
            ->select(['Resources.created_by'])
            // Correlate with the outer UPDATE query row: use the physical table name because CakePHP removes aliases in UPDATE/DELETE queries.
            ->where(['Resources.id' => new IdentifierExpression("$tbl.resource_id")])
            ->limit(1);

        // Update the secrets
        $update = $this->SecretsTable->updateQuery();
        $fn = $update->func();

        $update
            ->set('created_by', $fn->coalesce([$latestUserIdSub, $fallbackCreatedBySub]))
            // modified_by copies created by, it might later be used to persist the ID of the user who re-encrypt and sign the payload.
            ->set('modified_by', $fn->coalesce([$latestUserIdSub, $fallbackCreatedBySub]))
            ->where([
                $update->newExpr()->isNull('created_by'),
                $update->newExpr()->isNull('modified_by'),
            ])
            ->execute();
    }
}
