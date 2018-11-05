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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaAccountSettingsTestTrait
{
    public function mockMfaAccountSettings(string $user, array $data)
    {
        $userId = UuidFactory::uuid('user.id.' . $user);
        $data = json_encode($data);
        $AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
        $AccountSettings->createOrUpdateSetting($userId, MfaSettings::MFA, $data);
    }

    public function mockMfaOrgSettings(array $data, string $type = 'configure')
    {
        if ($type === 'configure') {
            Configure::write('passbolt.plugins.multiFactorAuthentication', $data);
        } else {
            throw new InternalErrorException('Org config backend type not supported: ' . $type);
        }
    }
}
