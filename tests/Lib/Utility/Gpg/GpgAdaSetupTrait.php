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
 * @since         3.3.0
 */
namespace App\Test\Lib\Utility\Gpg;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;

trait GpgAdaSetupTrait
{
    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend $gpg
     */
    protected $gpg;

    // Keys ids used in this test. Set in _gpgSetup.
    protected $adaKeyId;
    protected $serverKeyId;

    /**
     * Setup GPG and import the keys to be used in the tests
     */
    protected function gpgSetup()
    {
        // Make sure the keys are in the keyring
        // if needed we add them for later use in the tests
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }

        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->clearKeys();

        // Import the server key.
        $this->serverKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents(Configure::read('passbolt.gpg.serverKey.private')));
        $this->gpg->importKeyIntoKeyring(file_get_contents(Configure::read('passbolt.gpg.serverKey.public')));

        // Import the key of ada.
        $this->adaKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'));
    }
}
