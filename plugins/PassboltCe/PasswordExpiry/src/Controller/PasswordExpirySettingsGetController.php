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

namespace Passbolt\PasswordExpiry\Controller;

use App\Controller\AppController;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;

class PasswordExpirySettingsGetController extends AppController
{
    /**
     * Returns Password expiry settings.
     *
     * @param \Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface $getSettingsService Get settings service instance.
     * @return void
     */
    public function get(PasswordExpiryGetSettingsServiceInterface $getSettingsService): void
    {
        $this->assertJson();
        $settings = $getSettingsService->get();
        $this->success(__('The operation was successful.'), $settings->toArray());
    }
}
