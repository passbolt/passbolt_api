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

use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

interface PasswordExpiryGetSettingsServiceInterface
{
    /**
     * Returns Password expiry settings.
     *
     * @return \Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto
     * @throws \Cake\Http\Exception\InternalErrorException When value is not an array.
     */
    public function get(): PasswordExpirySettingsDto;
}
