<?php
declare(strict_types=1);

namespace Passbolt\Scim\Command;

use App\Command\PassboltCommand;
use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Service\Command\GetUserCommandService;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Passbolt\Scim\Service\ScimDeleteSettingsService;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Throwable;

/**
 * Settings command.
 */
class ScimSettingsCommand extends PassboltCommand
{
    /**
     * @param \App\Service\Command\GetUserCommandService $getUserCommandService
     */
    public function __construct(
        protected GetUserCommandService $getUserCommandService,
    ) {
        parent::__construct();
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('username', [
                'short' => 'u',
                'required' => true,
                'help' => __('The user name (email).'),
            ])
            ->addOption('id', [
                'short' => 'i',
                'required' => false,
                'help' => __('The SCIM settings uuid.'),
            ])
            ->addOption('delete', [
                'short' => 'd',
                'required' => false,
                'boolean' => true,
                'help' => __('Delete the SCIM settings.'),
            ]);

        return $parser;
    }

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
        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            $io->out('Please enable DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.');

            return $this->errorCode();
        }

        $user = $this->getUserCommandService->getUser($args);
        $id = $args->getOption('id');
        $delete = $args->getOption('delete');
        $uac = new UserAccessControl(Role::ADMIN, $user->id, $user->username);
        try {
            if ($delete) {
                $service = new ScimDeleteSettingsService();
                $service->deleteSettings($uac, $id);
                $io->success('Settings have been deleted successfully.');
            } else {
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
            }
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
