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
use Cake\Core\Configure;

$this->assign('title',	__('Account recovery started!'));
$this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'recover thank-you');
?>
<div class="grid">
    <div class="row">
        <div class="col6 push1 information">
            <h2><?php echo __('See you in your mailbox!'); ?></h2>
            <p>
                <?php echo __('We just sent you an email, please follow the instructions to recover your account on this device.'); ?>
            </p>
            <div class="message-wrapper">
                <p class="message warning">
                    <?= __('Recovery will only work if you have previously created an account on passbolt, followed the setup once and made a backup of your private key.'); ?>
                </p>
            </div>
            <p>
                <?= __('Keep the backup of your private key handy.'); ?>
                <?= __('Without it you will not be able to recover your account.'); ?>
            </p>
        </div>
        <div class="col4 push1 last">
            <div class="logo">
                <h1><span>Passbolt</span></h1>
            </div>
            <div class="register thank-you form feedback">
                <div class="graphical-feedback"><i class="fa fa-envelope-o huge"></i></div>
                <p>
                    <strong><?= __('Email sent!'); ?></strong>
                    <?= __('Please check your spam folder if you do not hear from us after a while.'); ?>
                </p>
            </div>
        </div>
    </div>
</div>
