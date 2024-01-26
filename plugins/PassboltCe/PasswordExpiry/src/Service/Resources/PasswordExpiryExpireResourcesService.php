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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Service\Resources;

use App\Model\Entity\Secret;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\TupleComparison;
use Cake\Event\EventDispatcherTrait;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

/**
 * Mark resources as expired if:
 *
 * @todo review the comment
 * - OR
 *  - some users lost access to resources by losing group access
 * - AND these users have viewed the secret in the past
 * - AND the permission is not expired yet
 */
class PasswordExpiryExpireResourcesService implements ResourcesExpireResourcesServiceInterface
{
    use EventDispatcherTrait;

    public const PASSWORD_EXPIRY_RESOURCES_EXPIRED_EVENT_NAME = 'PasswordExpiry.ExpireResourcesOnGroupsUpdateService.expire'; //phpcs:ignore

    protected PasswordExpiryValidationServiceInterface $passwordExpiryValidationService;

    /**
     * @param \App\Service\Resources\PasswordExpiryValidationServiceInterface $passwordExpiryValidationService password expiry validation service
     */
    public function __construct(PasswordExpiryValidationServiceInterface $passwordExpiryValidationService)
    {
        $this->passwordExpiryValidationService = $passwordExpiryValidationService;
    }

    /**
     * @inheritDoc
     */
    public function expireResourcesForSecrets(array $secrets = []): bool
    {
        if (empty($secrets)) {
            return false;
        }

        if (!$this->passwordExpiryValidationService->isExpiryAutomatic()) {
            return false;
        }

        $resourcesIdsToExpire = $this->findResourcesIdsToExpire($secrets);
        if (empty($resourcesIdsToExpire)) {
            return false;
        }

        $this->markResourcesAsExpired($resourcesIdsToExpire);
        $this->notifyResourcesOwners($resourcesIdsToExpire);

        return true;
    }

    /**
     * Find resources that need to be expired.
     * Note: The algorithm will return the resources that have a secret consumed irrespective of the timeline. If
     * a resource password was rotated after the resource secret was consumed, it will still be returned.
     * Note 2: Already expired resources are not returned.
     *
     * @param array<\App\Model\Entity\Secret> $secrets The deleted secrets
     * @return array returns the ids of the resources that were consumed
     */
    private function findResourcesIdsToExpire(array $secrets): array
    {
        $secretsTuplesDto = array_map(function (Secret $secret) {
            // @todo assert deleted secret is a secret entity
            return $secret->extract(['user_id', 'resource_id']);
        }, $secrets);

        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        return $resourcesTable->find('notExpired')
            ->select(['Resources.id'])
            ->join([
                'table' => TableRegistry::getTableLocator()->get('SecretAccesses')->getTable(),
                'type' => 'INNER',
                'conditions' => [
                    'secret_accesses.resource_id' => new IdentifierExpression('Resources.id'),
                    new TupleComparison(
                        ['secret_accesses.user_id', 'secret_accesses.resource_id'],
                        $secretsTuplesDto,
                        [],
                        'IN'
                    ),
                ],
            ])
            ->all()->extract('id')->toArray();
    }

    /**
     * Mark a list of resources as expired.
     *
     * @param array $resourceIds resources id to expire
     * @return void
     */
    private function markResourcesAsExpired(array $resourceIds): void
    {
        if (empty($resourceIds)) {
            return;
        }

        /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $ResourcesTable->updateAll(
            [PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => FrozenTime::now()->subSeconds(2)],
            ['Resources.id IN' => $resourceIds],
        );
    }

    /**
     * Notify resources owners about passwords expiry.
     *
     * @param string[] $resourceIds Resource ids that have just expired.
     * @return void
     */
    private function notifyResourcesOwners(
        array $resourceIds
    ): void {
        $this->dispatchEvent(
            self::PASSWORD_EXPIRY_RESOURCES_EXPIRED_EVENT_NAME,
            compact('resourceIds'),
            $this
        );
    }
}
