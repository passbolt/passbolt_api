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

$this->assign('title',	__('Recover a user account'));
$this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'recover');
$formContext = [
    'context' => [
        'validator' => [
            'Users' => 'recover',
        ]
    ]
];
?>
<div class="grid">
    <div class="row">
        <div class="col6 push1 information">
            <h2><?= __('Recover an existing account!'); ?></h2>
            <p>
                <?= __('You can use the account recovery to install passbolt on a new machine (or if you reinstalled your browser, etc).'); ?>
                <?= __('Enter your email address and we will send you an email to get started.'); ?>
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
            <div class="users register form">
                <?= $this->Form->create($user, $formContext);?>

                <fieldset>
                    <legend><?= __('Enter your email'); ?></legend>

                    <?= $this->Form->control('username'); ?>

                    <div class="submit-wrapper">
                        <input type="submit" class="button primary big" value="<?= __('start recovery'); ?>">
                    </div>
                </fieldset>
                <?= $this->Form->end();?>
            </div>
        </div>
    </div>
</div>