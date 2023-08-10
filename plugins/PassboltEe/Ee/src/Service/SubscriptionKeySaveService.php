<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */
namespace Passbolt\Ee\Service;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;
use Passbolt\Ee\Model\Table\SubscriptionsTable;

class SubscriptionKeySaveService
{
    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable $SubscriptionsTable
     */
    protected $SubscriptionsTable;

    /**
     * @var \Passbolt\Ee\Service\SubscriptionKeyValidateService $SubscriptionKeyValidateService
     */
    protected $SubscriptionKeyValidateService;

    /**
     * SubscriptionKeyGetService constructor.
     *
     * @param \Passbolt\Ee\Model\Table\SubscriptionsTable|null $Subscriptions subscriptions table
     */
    public function __construct(?SubscriptionsTable $Subscriptions = null)
    {
        /** @phpstan-ignore-next-line */
        $this->SubscriptionsTable = $Subscriptions
            ?? TableRegistry::getTableLocator()->get('Passbolt/Ee.Subscriptions');
        $this->SubscriptionKeyValidateService = new SubscriptionKeyValidateService();
    }

    /**
     * @param string|null $keyString key
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException if key format or signature or content is invalid
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     */
    public function save(?string $keyString, UserAccessControl $uac): SubscriptionKeyDto
    {
        if (!$uac->isAdmin()) {
            throw new ForbiddenException(__('Only administrators can update the subscription details.'));
        }
        $keyDto = $this->SubscriptionKeyValidateService->validate($keyString);
        $this->SubscriptionsTable->createOrUpdate($keyDto->data, $uac);

        return $keyDto;
    }
}
