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
use App\Utility\Purifier;

if (!isset($setupCase)) {
    $setupCase = 'install';
}
$this->assign('title',	__('Install'));
$this->assign('page_classes', 'setup install start');
$this->Html->css('themes/default/api_setup.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

// Only Firefox and Chrome are supported right now.
if ($browserName == 'firefox' || $browserName == 'chrome') {
    $pluginCheckTemplate = 'Public/Setup/supported';
} else {
    $pluginCheckTemplate = 'Public/Setup/unsupported';
}

// See. fetch('scriptBottom')
$this->start('scriptBottom');
// Load the javascript application.
$scriptOptions = ['fullBase' => true, 'cache-version' => Configure::read('passbolt.version')];
echo $this->Html->script('/js/vendors/jquery.min.js?v=' . Configure::read('passbolt.version'), $scriptOptions);
echo $this->Html->script('/js/setup/reload.js?v=' . Configure::read('passbolt.version'), $scriptOptions);
$this->end();
?>
<input type="hidden" id="js_setup_user_username" value="<?php echo $user->username; ?>"/>
<input type="hidden" id="js_setup_user_first_name" value="<?php echo Purifier::clean($user->profile->first_name); ?>"/>
<input type="hidden" id="js_setup_user_last_name" value="<?php echo Purifier::clean($user->profile->last_name); ?>"/>

<!-- first header -->
<div class="header first">
    <nav>
        <div class="primary navigation top">
            <!-- no top links at setup -->
        </div>
    </nav>
</div>

<!-- second header -->
<div class="header second">
    <div class="col1">
        <div class="logo no-img">
            <h1><span>Passbolt</span></h1>
        </div>
    </div>
    <div class="col2_3">
<?php if($setupCase === 'install'): ?>
        <h2 id="js_step_title"><?php echo __('Welcome to passbolt! Let\'s take 5 min to setup your system.') ?></h2>
<?php else: ?>
        <h2 id="js_step_title"><?php echo __('Account recovery: let\'s take 5 min to reconfigure your plugin!') ?></h2>
<?php endif;?>
    </div>
</div>

<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <div class="navigation wizard">
            <ul>
                <li class="selected">
                    <?php echo __('1. Get the plugin') ?>
                </li>
                <li class="disabled">
                    <?php echo __('2. Define your keys') ?>
                </li>
                <li class="disabled">
                    <?php echo __('3. Set a passphrase') ?>
                </li>
                <li class="disabled">
                    <?php echo __('4. Set a security token') ?>
                </li>
                <li class="disabled">
                    <?php echo __('5. Login!') ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- main -->
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <?php echo $this->element($pluginCheckTemplate); ?>
                </div>
                <div class="col5 last">
                </div>
            </div>
        </div>
    </div>
</div>
