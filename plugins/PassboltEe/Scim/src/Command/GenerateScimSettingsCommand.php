<?php
declare(strict_types=1);

namespace Passbolt\Scim\Command;

use App\Command\PassboltCommand;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\Scim\Service\ScimSetSettingsService;

/**
 * GenerateSettings command.
 */
class GenerateScimSettingsCommand extends PassboltCommand
{
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
            ]);

        return $parser;
    }

    /**
     * @param \Cake\Console\Arguments $args Arguments.
     * @return \App\Model\Entity\User|null Return user entity if user is found, null otherwise.
     */
    private function getUser(Arguments $args): ?User
    {
        $username = $args->getOption('username');
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable
            ->findByUsername($username)
            ->find('activeNotDeleted')
            ->find('notDisabled')
            ->first();

        return $user;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param Arguments $args The command arguments.
     * @param ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $user = $this->getUser($args);
        if (is_null($user)) {
            $io->abort(__('The user does not exist or is not active or is disabled.'));
        }

        $uac = new UserAccessControl(Role::ADMIN, $user->id, $user->username);

        try {
            $service = new ScimSetSettingsService($uac);
            $settings = $service->saveSettings([]);
            $io->success('Settings were generated successfully. Please check them');
            $io->out(json_encode($settings));
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $io);

            return $this->errorCode();
        }

        return $this->successCode();
    }
}
