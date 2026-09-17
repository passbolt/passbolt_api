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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Service;

use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Subscription\Model\Dto\SubscriptionKeyDto;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeySaveService;

/**
 * Orchestrates the in-product upgrade-to-PRO sequence and dispatches Edition.upgraded.
 */
class EditionUpgradeService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const EVENT_NAME = 'Edition.upgraded';

    /**
     * Persists the subscription key, sets the edition to PRO, then dispatches Edition.upgraded event.
     *
     * @param string|null $keyString Raw subscription key payload.
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @return \Passbolt\Subscription\Model\Dto\SubscriptionKeyDto The persisted subscription key DTO.
     * @throws \Cake\Http\Exception\ForbiddenException When the UAC is not admin.
     * @throws \Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException When the key is invalid.
     */
    public function upgrade(?string $keyString, UserAccessControl $uac): SubscriptionKeyDto
    {
        $uac->assertIsAdmin();

        /** @var \Passbolt\Edition\Model\Table\EditionOrganizationTable $editionTable */
        $editionTable = $this->fetchTable('Passbolt/Edition.EditionOrganization');

        /** @var \Passbolt\Subscription\Model\Dto\SubscriptionKeyDto $keyDto */
        $keyDto = $editionTable->getConnection()->transactional(
            function () use ($keyString, $uac): SubscriptionKeyDto {
                $dto = (new SubscriptionKeySaveService())->save($keyString, $uac);
                (new EditionSetService())->setToPro($uac);

                return $dto;
            },
        );

        $this->dispatchEvent(self::EVENT_NAME, [
            'subscriptionKey' => $keyDto,
            'uac' => $uac,
        ]);

        return $keyDto;
    }
}
