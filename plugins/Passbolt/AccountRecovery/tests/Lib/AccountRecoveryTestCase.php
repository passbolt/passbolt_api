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
namespace Passbolt\AccountRecovery\Test\Lib;

use App\Test\Lib\AppTestCase;

class AccountRecoveryTestCase extends AppTestCase
{
    protected $pluginEnabled;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->pluginEnabled = $this->isFeaturePluginEnabled('AccountRecovery');
        if (!$this->pluginEnabled) {
            $this->enableFeaturePlugin('AccountRecovery');
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if (!$this->pluginEnabled) {
            $this->disableFeaturePlugin('AccountRecovery');
        }
    }

    /**
     * Get a dummy valid public key
     *
     * @return string
     */
    protected function getDummyPublicKey(): string
    {
        return file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
    }

    /**
     * Get a dummy valid private key
     *
     * @return string
     */
    protected function getDummyPrivateKey(): string
    {
        return file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password_sig_ada.msg');
    }
}
