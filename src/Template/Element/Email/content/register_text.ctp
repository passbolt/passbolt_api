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
<h3>Welcome <?php echo $user->profile->first_name; ?>,</h3>
<br/>
<?= __('You just opened an account on passbolt at {0}',
    '<a href="' . Router::url('/',true) . '">' . Router::url('/',true) . '</a>');?>.
<?= __('Passbolt is an open source password manager.');?>
<?= __('It is designed to allow sharing credentials with your team without making compromises on security!'); ?>
<br/>
<br/>
<?= __('Let\'s take the next five minutes to get you started!'); ?><br/>
