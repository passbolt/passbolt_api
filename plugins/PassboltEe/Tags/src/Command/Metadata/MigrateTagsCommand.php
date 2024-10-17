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
namespace Passbolt\Tags\Command\Metadata;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Tags\Service\Metadata\MigrateAllV4TagsToV5Service;

class MigrateTagsCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Migrate V4 tags to V5.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        try {
            $result = (new MigrateAllV4TagsToV5Service())->migrate();
        } catch (BadRequestException $e) {
            $msg = $e->getMessage();
            $msg .= "\n";
            $msg .= __('To enable, set "allow_creation_of_v5_tags" metadata settings to true via `update_metadata_types_settings` command.'); // phpcs:ignore

            $io->abort($msg);
        }

        if (isset($result['migrated']) && count($result['migrated'])) {
            $this->success(__('{0} tags were migrated.', count($result['migrated'])), $io);
        }
        if ($result['success']) {
            $io->success(__('All tags successfully migrated.'));
        } else {
            $this->error(__('All tags could not be migrated.'), $io);
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
