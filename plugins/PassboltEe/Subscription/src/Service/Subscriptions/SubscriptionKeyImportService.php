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
namespace Passbolt\Subscription\Service\Subscriptions;

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Subscription\Model\Dto\SubscriptionKeyDto;

/**
 * Class SubscriptionKeyImportService
 */
class SubscriptionKeyImportService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @param string $fileName filename to import
     * @return \Passbolt\Subscription\Model\Dto\SubscriptionKeyDto
     * @throws \Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException if the submitted file or the contained subscription is not valid
     */
    public function importFromFile(string $fileName): SubscriptionKeyDto
    {
        if (!file_exists($fileName)) {
            throw new SubscriptionException(__('The file {0} could not be found.', $fileName));
        }
        if (!is_readable($fileName)) {
            throw new SubscriptionException(__('The file {0} could not be read.', $fileName));
        }
        $subscription = file_get_contents($fileName);
        if (!$subscription) {
            throw new SubscriptionException(__('The file {0} could not be read.', $fileName));
        }

        return $this->import($subscription);
    }

    /**
     * @param string|null $subscription subscription to import
     * @return \Passbolt\Subscription\Model\Dto\SubscriptionKeyDto
     * @throws \Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException if the submitted file or the contained subscription is not valid
     */
    public function import(?string $subscription): SubscriptionKeyDto
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $firstAdmin = $usersTable->findFirstAdmin();
        if ($firstAdmin === null) {
            throw new SubscriptionException(__('No active admins were found.'));
        }

        $saveService = new SubscriptionKeySaveService();
        $uac = new UserAccessControl(Role::ADMIN, $firstAdmin->id);

        return $saveService->save($subscription, $uac);
    }
}
