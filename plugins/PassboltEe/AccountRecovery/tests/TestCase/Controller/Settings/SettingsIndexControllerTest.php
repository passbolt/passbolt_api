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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Settings;

use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class SettingsIndexControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * Check that feature flag is absent from /settings.json if plugin is disabled
     */
    public function testAccountRecoverySettingsIndex_DisabledSuccess()
    {
        $this->disableFeaturePlugin('AccountRecovery');
        $this->getJson('/settings.json');
        $this->assertSuccess();
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->accountRecovery));
    }

    /**
     * Check that feature flag and props are present in /settings.json if plugin is enabled and user logged in
     */
    public function testAccountRecoverySettingsIndex_GetLUSuccess()
    {
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertSuccess();
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->accountRecovery->enabled);
        $this->assertTrue(isset($this->_responseJsonBody->passbolt->plugins->accountRecovery->version));
    }

    /**
     * Check that only feature flag is present in /settings.json if plugin is enabled and user not logged in
     */
    public function testAccountRecoverySettingsIndex_GetANSuccess()
    {
        $this->getJson('/settings.json');
        $this->assertSuccess();
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->accountRecovery->enabled);
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->accountRecovery->version));
    }
}
