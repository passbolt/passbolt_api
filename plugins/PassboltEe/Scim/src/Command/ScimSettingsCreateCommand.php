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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Command;

use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Throwable;

/**
 * Command to create the SCIM settings
 */
class ScimSettingsCreateCommand extends ScimSettingsCommand
{
    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);
        if (!$this->isDevelopEnvironment($io)) {
            return $this->errorCode();
        }

        $user = $this->getUserCommandService->getUser($args);
        $id = $args->getOption('id');
        $uac = new UserAccessControl(Role::ADMIN, $user->id, $user->username);
        try {
            $service = new ScimSetSettingsService($uac);
            $secretToken = ScimSetSettingsService::generateToken();
            $settings = $service->saveSettings([
                'scim_user_id' => $user->id,
                'setting_id' => UuidFactory::uuid(),
                'secret_token' => $secretToken,
            ], $id);
            $settings['secret_token'] = $secretToken;
            $io->success('Settings were successfully generated. Please check them');
            $io->out(print_r($settings, return: true));
        } catch (FormValidationException $fve) {
            $this->error($fve->getCode() . ':' . json_encode($fve->getErrors()), $io);

            return $this->errorCode();
        } catch (Throwable $e) {
            $this->error($e->getCode() . ':' . $e->getMessage(), $io);

            return $this->errorCode();
        }

        return $this->successCode();
    }
}
