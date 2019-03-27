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
 */

use Cake\Core\Configure;
use Cake\Event\EventManager;
use Passbolt\Log\Events\ActionsListener;

Configure::load('Passbolt/Log.version', 'default', true);

// Listen on controller actions.
$actions = new ActionsListener();
EventManager::instance()->on($actions);
