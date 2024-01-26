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

namespace Passbolt\PasswordExpiry\Service\Settings;

use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;

class PasswordExpiryDeleteSettingsService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    /**
     * Deletes Password expiry settings.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC.
     * @param string $id Setting ID.
     * @return bool
     * @throws \Cake\Http\Exception\NotFoundException When the ID is not found.
     * @throws \Cake\ORM\Exception\PersistenceFailedException When the entity could not be deleted.
     */
    public function delete(UserAccessControl $uac, string $id): bool
    {
        /** @var \Passbolt\PasswordExpiry\Model\Table\PasswordExpirySettingsTable $passwordExpirySettingsTable */
        $passwordExpirySettingsTable = $this->fetchTable('Passbolt/PasswordExpiry.PasswordExpirySettings');

        try {
            $setting = $passwordExpirySettingsTable->get($id);
        } catch (\Throwable $exception) {
            throw new NotFoundException(__('The password expiry setting does not exist.'));
        }

        $result = $passwordExpirySettingsTable->deleteOrFail($setting);

        /** Dispatch settings updated event. */
        $this->dispatchEvent(PasswordExpirySetSettingsService::EVENT_SETTINGS_UPDATED, compact('uac'), $setting);

        return $result;
    }
}
