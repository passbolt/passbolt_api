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
 * @since         4.1.0
 */
namespace Passbolt\Sso\Test\Lib;

use App\Utility\UuidFactory;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner;

trait MockAzureResourceOwnerTrait
{
    public function mockAzureResourceOwner(
        array $data = [],
        $emailAliasField = SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL
    ): AzureResourceOwner {
        if (empty($data)) {
            $data = [
                'oid' => UuidFactory::uuid(),
                'email' => 'ada@passbolt.com',
                'nonce' => SsoState::generate(),
                'auth_time' => time(),
            ];
        }

        return new AzureResourceOwner($data, $emailAliasField);
    }
}
