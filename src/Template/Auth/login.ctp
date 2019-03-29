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

$this->assign('title',	__('Login'));

$pageClass = 'login';
if (Configure::read('passbolt.registration.public') === true) {
    $pageClass .= ' public-registration';
}
$this->assign('pageClass', $pageClass);
$this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

// Only Firefox and Chrome are officially supported right now.
$pluginCheckTemplate = 'Public/Auth/unsupported';
$browser = strtolower($userAgent['Browser']['name']);
if ($browser == 'firefox' || $browser == 'chrome') {
    $pluginCheckTemplate = 'Public/Auth/supported';
}
?>
<div class="grid">
    <div class="row js_main-login-section">
        <?php echo $this->element($pluginCheckTemplate); ?>
    </div>
    <div class="row">
        <div class="col3 push1 github-block">
            <?php echo $this->element('Public/Promo/cloud'); ?>
        </div>
        <div class="col3 github-block">
            <?php echo $this->element('Public/Promo/pro'); ?>
        </div>
        <div class="col4 github-block push1 last">
            <?php echo $this->element('Public/Promo/github'); ?>
        </div>
    </div>
</div>
