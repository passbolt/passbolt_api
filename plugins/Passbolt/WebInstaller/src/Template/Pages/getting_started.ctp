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
$this->Html->css('themes/default/api_setup.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

$this->assign('page_classes', 'setup start');
?>
<?php echo $this->element('Navigation/empty'); ?>
<div class="page-row">
    <div class="grid grid-responsive-12">
        <div class="row">
            <div class="col12 last intro">
                <h1><?= __('Passbolt is not configured yet!') ?></h1>
                <h2><?= __('If you see this page, it means that passbolt is present on your server but not configured. You can configure it using one of the two methods below.') ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col6">
                <div class="big-choice">
                    <h3><?= __('Manual configuration') ?></h3>
                    <p>
                        <?= __('Choose this option if you want to configure passbolt step by step manually following the documentation.') ?>
                    </p>
                    <a href="https://help.passbolt.com/hosting/install" target="_blank" rel="noopener" class="dim button">
                        <i class="fa fa-cogs fa-fw"></i>  <?= __('Follow the documentation') ?>
                    </a>
                </div>
            </div>
            <div class="col6 last">
                <div class="big-choice">
                    <div class="ribbon"><span><?= __('POPULAR') ?></span></div>
                    <h3><?= __('Wizard configuration') ?></h3>
                    <p>
                        <?= __('Choose this option if you want to be guided by the configuration wizard and get started in no time.') ?>
                    </p>
                    <a href="<?= Router::url('install/system_check') ?>" class="button primary">
                        <i class="fa fa-magic fa-fw"></i> <?= __('Start the wizard') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
