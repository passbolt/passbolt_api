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
namespace App\Shell;

use App\Controller\Events\EmailNotificationsListener;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\Event\EventManager;

/**
 * App Shell Bootstrap
 *
 * Bootstrap for AppShell used as a singleton.
 * Since an AppShell can be initialized inside an AppShell (see ldap plugin),
 * this is done to avoid cascading effects on Event listeners
 * being added several times.
 */
class AppShellBootstrap
{
    /**
     * Instance of class used for singleton.
     * @var
     */
    private static $instance;

    /**
     * Init function.
     * @return AppShellBootstrap
     */
    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AppShellBootstrap();
            self::$instance->_bindEvents();
            self::$instance->_initUserAction();
        }

        return self::$instance;
    }

    /**
     * Bind events that are needed for AppShell.
     * @return void
     */
    private function _bindEvents()
    {
        $emails = new EmailNotificationsListener();
        EventManager::instance()->on($emails);
    }

    /**
     * Init the UserAction component if it's not already initialized.
     * This is to avoid errors while executing tasks that don't implement UserAction.
     * @return void
     */
    private function _initUserAction()
    {
        // Context will look like the example below:
        // CMD passbolt install --no-admin
        $args = $_SERVER['argv'];
        $args[0] = 'CMD';
        $context = implode(' ', $args);

        try {
            UserAction::getInstance();
        } catch (\Exception $e) {
            $uac = new UserAccessControl(Role::GUEST, null);
            UserAction::getInstance($uac, 'shell', $context);
        }
    }
}
