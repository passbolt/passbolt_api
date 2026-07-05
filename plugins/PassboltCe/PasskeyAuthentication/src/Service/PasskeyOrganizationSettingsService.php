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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Service;

use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;

/**
 * Reads and writes the organization-level "passkey login enabled" toggle (an admin setting stored in
 * organization_settings). When disabled, the passkey login button and the profile passkey screen are
 * hidden client-side; the stored credentials are kept untouched, so re-enabling restores everything.
 */
class PasskeyOrganizationSettingsService
{
    /**
     * @var string
     */
    public const SETTINGS_PROPERTY = 'passkeyAuthentication';

    /**
     * Whether passkey login is enabled for the organization (default: enabled).
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $setting = $OrganizationSettings->getByProperty(self::SETTINGS_PROPERTY);
        if ($setting === null) {
            return true;
        }
        $value = json_decode((string)$setting->get('value'), true);

        return !isset($value['enabled']) || (bool)$value['enabled'];
    }

    /**
     * Enable or disable passkey login for the organization (admin only).
     *
     * @param \App\Utility\UserAccessControl $uac User access control (must be an administrator)
     * @param bool $enabled Whether passkey login is enabled
     * @return void
     */
    public function setEnabled(UserAccessControl $uac, bool $enabled): void
    {
        $uac->assertIsAdmin();
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $OrganizationSettings->createOrUpdateSetting(
            self::SETTINGS_PROPERTY,
            (string)json_encode(['enabled' => $enabled]),
            $uac
        );
    }
}
