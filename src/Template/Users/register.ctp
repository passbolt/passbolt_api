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
use Cake\Routing\Router;
use Cake\Core\Configure;

$this->assign('title',	__('Register'));
$this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'register');
$formContext = [
    'context' => [
        'validator' => [
            'Users' => 'register',
            'Profiles' => 'register'
        ]
    ]
];
?>
<div class="grid">
    <div class="row">
        <div class="col6 push1 information">
            <h2><?= __('Try passbolt demo today!'); ?></h2>
            <p>
                <?= __('Enter your details in the form.'); ?>

                <?= __('We will send you an email to get you started.'); ?>

            </p>
<?= $this->element('Public/disclaimer_legal'); ?>
        </div>
        <div class="col4 push1 last">
            <div class="logo">
                <h1><span>Passbolt</span></h1>
            </div>
            <div class="users register form">
                <?= $this->Form->create($user, $formContext);?>

                <fieldset>
                    <legend><?= __('Please enter your username and password'); ?></legend>
                    <?= $this->Form->control('profile.first_name', ['placeholder' => __('First name')]); ?>

                    <?= $this->Form->control('profile.last_name', ['placeholder' => __('Last name')]); ?>

                    <?= $this->Form->control('username', ['placeholder' => __('mail@domain.com')]); ?>

                    <p>
                        <input type="checkbox" name="disclaimer" id="disclaimer" value="value" required="required">
                        <label for="disclaimer" style="font-size:.9em"><?= __('I understand the disclaimer. I agree with the Terms of Service and Privacy Policy.'); ?></label>
                    </p>
                    <div class="submit-wrapper">
                        <input type="submit" class="button primary big" value="<?= __('register'); ?>">
                        <a href="<?= Router::url('/login', true); ?>" class="secondary"><?= __('already a member?'); ?></a>
                    </div>
                </fieldset>
                <?= $this->Form->end();?>

            </div>
        </div>
    </div>
</div>
