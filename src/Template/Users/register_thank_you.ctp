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
$this->assign('title',	__('Thank you'));
$this->Html->css('login.min', ['block' => 'css']);
$this->assign('pageClass', 'register thank-you');
?>
<div class="grid">
    <div class="row">
        <div class="col6 push1 information">
            <h2><?= __('Thank you'); ?></h2>
            <p>
                <?= __('Thank you for giving passbolt a test run! We just sent you an email, please follow the instructions to get started.'); ?>
            </p>
            <?= $this->element('public/disclaimer_legal'); ?>
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
