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
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Passbolt\Log\Model\Table\EntitiesHistoryTable;
use Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable;

class PopulateSecretRevisionsForExistingSecretsService
{
    private ResourcesTable $ResourcesTable;

    private EntitiesHistoryTable $EntitiesHistoryTable;

    private SecretRevisionsTable $SecretRevisions;

    private SecretsTable $SecretsTable;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->EntitiesHistoryTable = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $this->SecretRevisions = TableRegistry::getTableLocator()->get('Passbolt/SecretRevisions.SecretRevisions');
        $this->SecretsTable = TableRegistry::getTableLocator()->get('Secrets');
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

        // Select resources to insert
        $fn = $this->ResourcesTable->find()->func();
        $resourcesSelectQuery = $this->ResourcesTable
            ->find()
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
            ])
            ->all();

        if ($resourcesSelectQuery->count() < 1) {
            return 0;
        }

        // Insert secret revisions
        $insertQuery = $this->SecretRevisions->insertQuery();
        $insertQuery = $insertQuery
            ->insert(['id', 'resource_id', 'resource_type_id', 'created', 'modified', 'created_by', 'modified_by']);

        foreach ($resourcesSelectQuery as $resource) {
            $insertQuery->values([
                'id' => Text::uuid(),
                'resource_id' => $resource->get('id'),
                'resource_type_id' => $resource->get('resource_type_id'),
                'created' => DateTime::now()->format('Y-m-d H:i:s'),
                'modified' => DateTime::now()->format('Y-m-d H:i:s'),
                'created_by' => $resource->get('created_by'),
                'modified_by' => $resource->get('modified_by'),
            ]);
        }

        return $insertQuery->execute()->rowCount();
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
