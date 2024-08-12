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
namespace Passbolt\JwtAuthentication\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;

class CreateJwtKeysCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Create a JWT key pair.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('force', [
                'help' => 'Override the key files if found.',
                'default' => 'false',
                'short' => 'f',
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $force = $args->getOption('force');
        $service = new JwtKeyPairService();

        if ($service->keyPairExists() && !$force) {
            if (file_exists($service->getPublicKeyPath())) {
                $io->warning('Public key path: ' . $service->getPublicKeyPath());
            }
            if (file_exists($service->getPublicKeyPath())) {
                $io->warning('Secret key path: ' . $service->getSecretKeyPath());
            }
            $msg = "A JWT key pair was found. \n";
            $msg .= "Use the force option to overwrite with a fresh new pair. \n";
            $msg .= 'This will log out all users currently logged in with JWT Authentication.';
            $io->abort($msg);
        }

        try {
            $service->createKeyPair($force);
            $service->validateKeyPair();
        } catch (InvalidJwtKeyPairException $e) {
            $io->abort($e->getMessage());
        }

        $io->success('A JWT key pair was successfully created.');
        $io->success('Public key path: ' . $service->getPublicKeyPath());
        $io->success('Secret key path: ' . $service->getSecretKeyPath());

        return $this->successCode();
    }
}
