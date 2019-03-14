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
use App\View\Helper\HealthcheckHtmlHelper;
use Cake\Core\Configure;

$this->assign('title',	__('Health checks'));
$this->Html->css('themes/default/api_main.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'status');

$healthcheck = new HealthcheckHtmlHelper();
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col8">
            <h2><?php echo __('Passbolt API Status') ?></h2>
            <?php
            $healthcheck->assertEnvironment($body);
            ?>
            <!-- if the javascript does not load this message will be shown -->
            <div id="url-rewriting-warning" class="message error">
                <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                <a target="_blank" rel="noopener" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
            </div>
            <?php
            if ($body['ssl']):
                echo '<div class="message success">' . __('SSL access is enabled.') . '</div>';
            else:
                echo '<div class="message error">' . __('SSL access is not enabled.') . '</div>';
            endif;
            ?>
            <?php
            $healthcheck->assertConfigFiles($body);
            $healthcheck->assertCore($body);
            $healthcheck->assertDatabase($body);
            $healthcheck->assertGpg($body);
            $healthcheck->assertApplication($body);
            ?>
        </div>
        <div class="col4 last" style="margin-top:2.8em;">
            <h3>What is this page?</h3>
            <p>
                This page is available to help administrators diagnose if something is wrong
                with a passbolt installation and help keeping it secure.
            </p>
            <p>
                It is also possible to perform a healt check using the command line tools as follow:
            </p>
            <code>
        <pre>
sudo su -s /bin/bash -c "./bin/cake passbolt healthcheck" www-data
        </pre>
            </code>
            <h3>Something wrong?</h3>
            <p>Hang in there! You can find more information on how to install and update passbolt
                in the official online help.</p>
            <a href="https://www.passbolt.com/help/" target="_blank" rel="noopener" class="button primary big">
                <i class="fa fa-fw fa-life-saver"></i>
                Help
            </a>
        </div>
    </div>
</div>