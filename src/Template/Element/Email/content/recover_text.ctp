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
use Cake\Routing\Router;
?>
<h3><?= __('Welcome back {0}', $user->profile->first_name); ?>,</h3><br/>
<?= __('You have just requested to recover your {0}passbolt{1} account on this device.',
    '<a href="' . Router::url('/',true) . '">', '</a>'); ?>
<?= __('Click on the link below to proceed.'); ?>

