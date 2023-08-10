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

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;

/**
 * Class SubscriptionKeyImportService
 */
class SubscriptionKeyImportService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @param string $fileName filename to import
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException if the submitted file or the contained subscription is not valid
     */
    public function import(string $fileName): SubscriptionKeyDto
    {
        $this->validateFile($fileName);

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $firstAdmin = $usersTable->findFirstAdmin();
        if ($firstAdmin === null) {
            throw new SubscriptionException(__('No active admins were found.'));
        }

        $saveService = new SubscriptionKeySaveService();
        $uac = new UserAccessControl(Role::ADMIN, $firstAdmin->id);

        return $saveService->save(file_get_contents($fileName), $uac);
    }

    /**
     * @param string $fileName File to validate
     * @return void
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException if the file is not readable or not found.
     */
    private function validateFile(string $fileName): void
    {
        if (!file_exists($fileName)) {
            throw new SubscriptionException(__('The file {0} could not be found.', $fileName));
        }
        if (!is_readable($fileName)) {
            throw new SubscriptionException(__('The file {0} could not be read.', $fileName));
        }
    }
}
