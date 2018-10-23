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
namespace App\Shell;

use App\Controller\Events\EmailNotificationsListener;
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
}
