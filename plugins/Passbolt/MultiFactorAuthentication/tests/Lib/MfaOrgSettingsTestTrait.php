<?php
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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaOrgSettingsTestTrait
{
    /**
     * @param array $data org settings ['providers' => [...
     * @param string $type configure or database
     * @param UserAccessControl $user optional, only used when $type is database
     */
    public function mockMfaOrgSettings(array $data, string $type = 'configure', UserAccessControl $user = null)
    {
        if ($type === 'configure') {
            Configure::write('passbolt.plugins.multiFactorAuthentication', $data);
        } else {
            $data = json_encode($data);
            $OrgSettings = TableRegistry::get('OrganizationSettings');
            $OrgSettings->createOrUpdateSetting(MfaSettings::MFA, $data, $user);
        }
    }
}