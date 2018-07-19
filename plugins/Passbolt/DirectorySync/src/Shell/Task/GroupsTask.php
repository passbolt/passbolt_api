<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Shell\Task;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Shell\AppShell;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryFactory;

class GroupsTask extends AppShell
{
    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Groups');
        $this->loadModel('Users');
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @throws \Exception
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Sync groups'))
            ->addOption('dry-run', [
                'help' => 'Don\'t save the changes',
                'default' => 'true',
                'boolean' => true,
            ]);
        return $parser;
    }

    /**
     * Main shell entry point
     *
     * @return bool true if successful
     */
    public function main()
    {
        try {
            $directory = DirectoryFactory::get();
        } catch(\Exception $exception) {
            $this->abort($exception->getMessage());
            return false;
        }

        $groups = $directory->getGroups();
        $defaultGroupAdmin = $this->getDefaultGroupAdmin();

        // Get first admin.
        // TODO: find a solution. It should not be this user that creates groups.
        // Should we have a ldap user ????
        $firstAdmin = $this->Users->findFirstAdmin();

        foreach($groups as $id => $data) {
            $data['groups_users'][] = [
                'user_id' => $defaultGroupAdmin->id,
                'is_admin' => true,
            ];
            try {
                $groupSaved = $this->Groups->create($data, new UserAccessControl(Role::ADMIN, $firstAdmin->id));
            } catch (ValidationException $e) {
                $this->err("Group {$data['name']} could not be saved: " . $e->getMessage());
                $entity = $e->getEntity();
                $this->err($entity->getErrors());
            }

            if ($groupSaved) {
                $this->info("Group {$data['name']} has been saved");
            }
        }
        return true;
    }

    /**
     * Get default group administrator
     */
    public function getDefaultGroupAdmin() {
        $groupAdmin = Configure::read('passbolt.plugins.directorySync.adminUser');
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin = $this->Users->find()
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.username' => $groupAdmin
                ]);
            if (!empty($groupAdmin)) {
                return $groupAdmin;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }

    /**
     * Display the entity validation errors
     *
     * @param array $errors validation errors
     * @return void
     */
    protected function _displayValidationError($errors)
    {
        foreach ($errors as $fieldname => $error) {
            foreach ($error as $rule => $message) {
                if (is_array($message)) {
                    $this->_displayValidationError($error);
                    break;
                } else {
                    $message = '- ' . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $this->out($message);
                }
            }
        }
    }
}