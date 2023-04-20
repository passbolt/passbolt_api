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
use Cake\Routing\Router;

$this->assign('title', __('Passbolt is not configured.'));
$this->Html->css('themes/default/api_webinstaller.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

$this->assign('page_classes', 'setup start');
?>
<?php echo $this->element('Navigation/empty'); ?>
<div class="page-row">
    <div class="grid grid-responsive-12">
        <div class="row">
            <div class="col12 last intro">
                <h1><?= __('Passbolt is not configured yet!') ?></h1>
                <h2><?= __('If you see this page, it means that passbolt is present on your server but not configured. Click on "Get Started" to launch the configuration wizard.') ?></h2>
                <p>&nbsp;</p>
                <p>
                    <a href="<?= Router::url('/install/system_check') ?>" class="button primary big">
                        <span class="svg-icon magic-wand icon-only"><svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1254 581l293-293-107-107-293 293zm447-293q0 27-18 45l-1286 1286q-18 18-45 18t-45-18l-198-198q-18-18-18-45t18-45l1286-1286q18-18 45-18t45 18l198 198q18 18 18 45zm-1351-190l98 30-98 30-30 98-30-98-98-30 98-30 30-98zm350 162l196 60-196 60-60 196-60-196-196-60 196-60 60-196zm930 478l98 30-98 30-30 98-30-98-98-30 98-30 30-98zm-640-640l98 30-98 30-30 98-30-98-98-30 98-30 30-98z"></path></svg></span>
                        <?= __('Get Started') ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
