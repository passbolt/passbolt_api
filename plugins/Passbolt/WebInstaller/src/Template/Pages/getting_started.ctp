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

$this->assign('title',	__('Passbolt is not configured.'));
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
                    <a href="<?= Router::url('install/system_check') ?>" class="button primary big">
                        <i class="fa fa-magic fa-fw"></i> <?= __('Get Started') ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
