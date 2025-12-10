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
namespace Passbolt\SecretRevisions\Service\Secrets;

use App\Model\Table\ResourcesTable;
use App\Model\Table\SecretsTable;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Passbolt\Log\Model\Table\EntitiesHistoryTable;
use Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable;

class PopulateSecretRevisionsForExistingSecretsService
{
    /**
     * Default number of records to process per batch to limit memory usage.
     */
    public const DEFAULT_BATCH_SIZE = 1000;

    private ResourcesTable $ResourcesTable;

    private EntitiesHistoryTable $EntitiesHistoryTable;

    private SecretRevisionsTable $SecretRevisions;

    private SecretsTable $SecretsTable;

    private int $batchSize;

    /**
     * Constructor.
     *
     * @param int|null $batchSize Number of records to process per batch. Defaults to DEFAULT_BATCH_SIZE.
     */
    public function __construct(?int $batchSize = null)
    {
        $this->ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->EntitiesHistoryTable = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $this->SecretRevisions = TableRegistry::getTableLocator()->get('Passbolt/SecretRevisions.SecretRevisions');
        $this->SecretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->batchSize = $batchSize ?? self::DEFAULT_BATCH_SIZE;
    }

    /**
     * @return void
     */
    public function populate(): void
    {
        $rowsAdded = $this->populateSecretRevisionsTable();

        if ($rowsAdded > 0) {
            $this->updateSecretsTable();
        }
    }

    /**
     * @return int No. of rows added.
     */
    private function populateSecretRevisionsTable(): int
    {
        $resourcesTableAlias = $this->ResourcesTable->getAlias();

        // Latest user responsible for creating or updating this secret (by secret id)
        $entitiesHistoryQuery = $this->EntitiesHistoryTable->find();
        $latestUserIdSubquery = $entitiesHistoryQuery
            ->select(['ActionLogs.user_id'])
            ->innerJoin(['SecretsHistory' => 'secrets_history'], [
                'SecretsHistory.id' => new IdentifierExpression('EntitiesHistory.foreign_key'),
                'EntitiesHistory.foreign_model' => 'SecretsHistory',
            ])
            // Join the action log that originated from the secret update
            ->innerJoin(['ActionLogs' => 'action_logs'], [
                'ActionLogs.id' => new IdentifierExpression('EntitiesHistory.action_log_id'),
                $entitiesHistoryQuery->newExpr()->isNotNull('ActionLogs.user_id'),
            ])
            ->innerJoin(['Actions' => 'actions'], [
                'Actions.id' => new IdentifierExpression('ActionLogs.action_id'),
                'Actions.name' => 'ResourcesUpdate.update',
            ])
            // Correlate with the outer SELECT query row: use the physical table name because CakePHP removes aliases in UPDATE/DELETE queries.
            ->where(['SecretsHistory.resource_id' => new IdentifierExpression("$resourcesTableAlias.id")])
            // Use the most recent relevant action.
            ->orderByDesc('EntitiesHistory.created')
            ->limit(1);

        // Select resources to insert - use cursor-based iteration instead of loading all into memory
        $fn = $this->ResourcesTable->find()->func();
        $resourcesSelectQuery = $this->ResourcesTable
            ->find()
            ->disableHydration()
            ->select([
                'Resources.id',
                'Resources.resource_type_id',
                'created_by' => $fn->coalesce([$latestUserIdSubquery, new IdentifierExpression("$resourcesTableAlias.created_by")]), // phpcs:ignore
                'modified_by' => $fn->coalesce([$latestUserIdSubquery, new IdentifierExpression("$resourcesTableAlias.created_by")]), // phpcs:ignore
            ])
            ->leftJoin(['SecretRevisions' => 'secret_revisions'], [
                'SecretRevisions.resource_id' => new IdentifierExpression('Resources.id'),
            ])
            // Filter out resources with deleted resource types
            ->innerJoinWith('ResourceTypes', function ($q) {
                return $q->where([$q->expr()->isNull('ResourceTypes.deleted')]);
            })
            ->where([
                // Filter out deleted resources
                'Resources.deleted' => false,
                // Only select resources without secret revisions
                $this->ResourcesTable->find()->newExpr()->isNull('SecretRevisions.resource_id'),
            ]);

        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $totalRowsAdded = 0;
        $batchCount = 0;

        $connection->begin();
        foreach ($resourcesSelectQuery as $resource) {
            $connection->insert('secret_revisions', [
                'id' => Text::uuid(),
                'resource_id' => $resource['id'],
                'resource_type_id' => $resource['resource_type_id'],
                'created' => DateTime::now()->format('Y-m-d H:i:s'),
                'modified' => DateTime::now()->format('Y-m-d H:i:s'),
                'created_by' => $resource['created_by'],
                'modified_by' => $resource['modified_by'],
            ]);

            $totalRowsAdded++;
            $batchCount++;

            // Commit and restart transaction every "batchSize" no. of rows
            if ($batchCount >= $this->batchSize) {
                $connection->commit();
                $connection->begin();
                $batchCount = 0;
                // reset limit
                set_time_limit(30);
            }
        }
        $connection->commit();

        return $totalRowsAdded;
    }

    /**
     * Link secret_revision_id in secrets table.
     *
     * @return int No. of rows updated.
     */
    private function updateSecretsTable(): int
    {
        $secretsTableName = $this->SecretsTable->getTable();

        $subquery = $this->SecretRevisions
            ->find()
            ->select(['id'])
            ->where(['SecretRevisions.resource_id' => new IdentifierExpression("$secretsTableName.resource_id")])
            ->limit(1);

        $updateQuery = $this->SecretsTable->updateQuery();
        $updateQuery
            ->set('secret_revision_id', $subquery)
            ->where([$updateQuery->newExpr()->isNull('secret_revision_id')]);

        return $updateQuery->execute()->rowCount();
    }
}
