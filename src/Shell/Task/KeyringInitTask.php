<?php
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
 * @since         2.0.0
 */
namespace App\Shell\Task;

use App\Shell\AppShell;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class KeyringInitTask extends AppShell
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Installation shell for the passbolt application.'));

        return $parser;
    }

    /**
     * Main
     *
     * @return bool
     */
    public function main()
    {
        // Root user is not allowed to execute this command.
        if (!$this->assertNotRoot()) {
            return false;
        }

        try {
            $filePath = Configure::read('passbolt.gpg.serverKey.private');
            if (!file_exists($filePath)) {
                throw new Exception(__('The file does not exist: {0}', $filePath));
            }
            $armoredKey = file_get_contents($filePath);
            if ($armoredKey === false) {
                throw new Exception(__('Could not read the file: {0}', $filePath));
            }
            // Import the private key in the GPG keyring
            $gpg = OpenPGPBackendFactory::get();

            $this->out('Importing ' . $filePath);
            $gpg->importKeyIntoKeyring($armoredKey);
        } catch (Exception $e) {
            $this->_error($e->getMessage());
            $this->_error('The server OpenPGP key could not be imported into the GnuPG keyring.');

            return false;
        }

        $this->_success('Keyring init OK');

        return true;
    }
}
