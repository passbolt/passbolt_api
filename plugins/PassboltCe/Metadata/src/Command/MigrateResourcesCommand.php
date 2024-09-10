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
use Passbolt\Metadata\Service\Migration\MigrateAllV4ResourcesToV5Service;

class MigrateResourcesCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Migrate V4 resources to V5.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $migrateService = (new MigrateAllV4ResourcesToV5Service());
        $result = $migrateService->migrate();

        if ($result['success']) {
            $io->success(__('All resources successfully migrated.'));
        } else {
            $this->error(__('All resources could not migrated.'), $io);
            $this->error(__('See errors:'), $io);
            $errors = $result['errors'];
            foreach ($errors as $error) {
                $this->error($error['error_message'], $io);
            }

            return $this->errorCode();
        }

        return $this->successCode();
    }
}
