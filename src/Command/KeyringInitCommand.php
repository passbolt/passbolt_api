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
 * @since         2.0.0
 */
namespace App\Command;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class KeyringInitCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('GnuPG Keyring init shell for the passbolt application.'));

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Root user is not allowed to execute this command.
        if (!$this->assertNotRoot($io)) {
            return $this->errorCode();
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
            // Import the private key in the OpenPGP keyring
            $gpg = OpenPGPBackendFactory::get();

            $io->out('Importing ' . $filePath);
            $gpg->importKeyIntoKeyring($armoredKey);
        } catch (Exception $e) {
            $this->error($e->getMessage(), $io);
            $this->error('Could not import the server OpenPGP key into the keyring.', $io);

            return $this->errorCode();
        }

        $this->success('Keyring init OK', $io);

        return $this->successCode();
    }
}
