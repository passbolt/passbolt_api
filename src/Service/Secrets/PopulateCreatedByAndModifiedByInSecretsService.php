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

use App\Model\Entity\Resource;
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
            ->set('created_by',  $fn->coalesce([$latestUserIdSub, $fallbackCreatedBySub]))
            // modified_by copies created by, it might later be used to persist the ID of the user who re-encrypt and sign the payload.
            ->set('modified_by', $fn->coalesce([$latestUserIdSub, $fallbackCreatedBySub]))
            ->execute();
    }

    /**
     * @return void
     */
    public function populateBackup(): void
    {
//        $resourcesQuery = $this->ResourcesTable->find();
//        $resources = $resourcesQuery
//            ->where(['Resources.deleted' => false])
//            ->innerJoinWith('ResourceTypes', function ($q) {
//                return $q->where([$q->expr()->isNull('ResourceTypes.deleted')]);
//            })
//            ->all();

//        if ($resources->count() === 0) {
//            return;
//        }
//
//        $resources
//            ->chunk(1000)
//            ->each(function ($resourcesBatch): void {
//                $this->populateSecrets($resourcesBatch);
//            });

        $entitiesHistoryQuery = $this->EntitiesHistoryTable->find();
        // oldest for created_by
        $oldestSubQuery = $entitiesHistoryQuery
            ->select(['ActionLogs.user_id'])
            ->innerJoin(['SecretsHistory' => 'secrets_history'], [
                'SecretsHistory.id' => new IdentifierExpression('EntitiesHistory.foreign_key'),
                'EntitiesHistory.foreign_model' => 'SecretsHistory',
            ])
            ->innerJoin(['ActionLogs' => 'action_logs'], [
                'ActionLogs.id' => new IdentifierExpression('EntitiesHistory.action_log_id'),
                // Eliminate data integrity issue where user isn't present to not get surprises down the line
                $entitiesHistoryQuery->newExpr()->isNotNull('ActionLogs.user_id'),
            ])
            ->where(['SecretsHistory.id' => new IdentifierExpression('Secrets.id')])
            ->orderByAsc('ActionLogs.created')
            ->limit(1);
        // latest for modified_by
        $latestSubQuery = $entitiesHistoryQuery
            ->select(['ActionLogs.user_id'])
            ->innerJoin(['SecretsHistory' => 'secrets_history'], [
                'SecretsHistory.id' => new IdentifierExpression('EntitiesHistory.foreign_key'),
                'EntitiesHistory.foreign_model' => 'SecretsHistory',
            ])
            ->innerJoin(['ActionLogs' => 'action_logs'], [
                'ActionLogs.id' => new IdentifierExpression('EntitiesHistory.action_log_id'),
                // Eliminate data integrity issue where user isn't present to not get surprises down the line
                $entitiesHistoryQuery->newExpr()->isNotNull('ActionLogs.user_id'),
            ])
            ->where(['SecretsHistory.id' => new IdentifierExpression('Secrets.id')])
            ->orderByDesc('ActionLogs.created')
            ->limit(1);

        $secretsQuery = $this->SecretsTable->find();
        $secretsWithCreatorAndModifierQuery = $secretsQuery
            ->select([
                'secret_id' => 'Secrets.id',
                'created_by' => $secretsQuery->func()->coalesce([
                    $oldestSubQuery,
                    // Fallback to resources table value
                    new IdentifierExpression('Resources.created_by'),
                ]),
                'modified_by' => $secretsQuery->func()->coalesce([
                    $latestSubQuery,
                    // Fallback to resources table value
                    new IdentifierExpression('Resources.created_by'),
                ]),
            ])
            ->leftJoin(['Resources' => 'resources'], [
                'Resources.id' => new IdentifierExpression('Secrets.resource_id'),
            ])
            ->orderByAsc('Secrets.created');

        if ($secretsWithCreatorAndModifierQuery->count() === 0) {
            return;
        }

        $secretsUpdateQuery = $this->SecretsTable->updateQuery();
        $count = $secretsUpdateQuery
            ->innerJoin(['SecretsWithCreatorAndModifier' => $secretsWithCreatorAndModifierQuery], [
                'Secrets.id' => new IdentifierExpression('SecretsWithCreatorAndModifier.secret_id'),
            ])
            ->set('Secrets.created_by', new IdentifierExpression('SecretsWithCreatorAndModifier.created_by'))
            ->set('Secrets.modified_by', new IdentifierExpression('SecretsWithCreatorAndModifier.modified_by'))
            ->execute();
    }

    /**
     * @param array $resources Resources batch to update secrets.
     * @return void
     */
    private function populateSecrets(array $resources): void
    {
        foreach ($resources as $resource) {
            $this->populateCreatedByAndModifiedBySecret($resource);
        }
    }

    /**
     * @param Resource $resource Resource's secrets to update.
     * @return void
     */
    private function populateCreatedByAndModifiedBySecret(Resource $resource): void
    {
        $query = $this->EntitiesHistoryTable->find();

        $entitiesHistory = $query
            ->select([
                'Secrets.id',
                'EntitiesHistory.crud',
                'EntitiesHistory.created',
                'ActionLogs.user_id',
                'ActionLogs.created',
            ])
            ->innerJoin(['SecretsHistory' => 'secrets_history'], [
                'SecretsHistory.id' => new IdentifierExpression('EntitiesHistory.foreign_key'),
                'EntitiesHistory.foreign_model' => 'SecretsHistory',
            ])
            ->innerJoin(['Secrets' => 'secrets'], [
                'Secrets.id' => new IdentifierExpression('SecretsHistory.id'),
            ])
            ->innerJoin(['ActionLogs' => 'action_logs'], [
                'ActionLogs.id' => new IdentifierExpression('EntitiesHistory.action_log_id'),
                // Eliminate data integrity issue where user isn't present to not get surprises down the line
                $query->newExpr()->isNotNull('ActionLogs.user_id'),
            ])
            ->where(['SecretsHistory.resource_id' => $resource->id])
            ->orderByAsc('EntitiesHistory.created')
            ->all();

        if ($entitiesHistory->count() > 0) {
            $entitiesHistory = $entitiesHistory->toArray();
            // The action logs of oldest entry is most likely performed by the initial creator of the secret.
            // And the latest action log user entry we take it as modified.
            $oldestHistory = array_key_first($entitiesHistory);
            $latestHistory = array_key_last($entitiesHistory);
            $createdBy = $entitiesHistory[$oldestHistory]['ActionLogs']['user_id'];
            $modifiedBy = $entitiesHistory[$latestHistory]['ActionLogs']['user_id'];
        } else {
            $resourceCreatedBy = $this->ResourcesTable->find()
                ->select(['created_by'])
                ->where(['id' => $resource->id])
                ->firstOrFail();
            $createdBy = $modifiedBy = $resourceCreatedBy->get('created_by');
        }

        // Update secrets table
        $this->SecretsTable->updateAll(
            [
                'created_by' => $createdBy,
                'modified_by' => $modifiedBy,
            ],
            ['resource_id' => $resource->id]
        );
    }
}
