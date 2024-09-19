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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Passbolt\Metadata\Service\Migration\GenerateDummyMetadataKeyService;

class GenerateDummyMetadataKeyCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return 'Generate a metadata private/public key pair.'
            . 'Share it with server and users keys.'
            . 'For testing purpose ONLY.'
            . 'Requires both DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.';
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            $io->out('Please enable DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.');

            return $this->errorCode();
        }

        try {
            $key = (new GenerateDummyMetadataKeyService())->generate();
            $io->out('New key generated and encrypted for users: ' . $key->fingerprint);
        } catch (\Exception $e) {
            $io->err($e->getMessage());

            return $this->errorCode();
        }

        return $this->successCode();
    }
}
