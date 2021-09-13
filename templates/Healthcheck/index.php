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
            <h1><?php echo __('Passbolt API Status') ?></h1>
            <?php
            $healthcheck->assertEnvironment($body);
            ?>
            <!-- if the javascript does not load this message will be shown -->
            <div id="url-rewriting-warning" class="message error">
                <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                <a target="_blank" rel="noopener noreferrer" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
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
            <div class="sidebar-help transparent">
                <h3>What is this page?</h3>
                <p>This page is available to help administrators diagnose if something is wrong with a passbolt installation and help keeping it secure.</p>
                <p>It is also possible to perform a healt check using the command line tools as follow:</p>
                <code>
        <pre>
sudo su -s /bin/bash -c "./bin/cake passbolt \
            healthcheck" www-data
        </pre>
                </code>
            </div>

            <div class="sidebar-help transparent">
                <h3>Something wrong?</h3>
                <p>Hang in there! You can find more information on how to install and update passbolt in the official online help.</p>
                <a class="button medium"" href="https://help.passbolt.com" target="_blank" rel="noopener noreferrer">
                    <span>
                        <span class="svg-icon life-ring icon-only">
                            <svg width="1792" height="1792" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 504c136.967 0 248-111.033 248-248S392.967 8 256 8 8 119.033 8 256s111.033 248 248 248zm-103.398-76.72l53.411-53.411c31.806 13.506 68.128 13.522 99.974 0l53.411 53.411c-63.217 38.319-143.579 38.319-206.796 0zM336 256c0 44.112-35.888 80-80 80s-80-35.888-80-80 35.888-80 80-80 80 35.888 80 80zm91.28 103.398l-53.411-53.411c13.505-31.806 13.522-68.128 0-99.974l53.411-53.411c38.319 63.217 38.319 143.579 0 206.796zM359.397 84.72l-53.411 53.411c-31.806-13.505-68.128-13.522-99.973 0L152.602 84.72c63.217-38.319 143.579-38.319 206.795 0zM84.72 152.602l53.411 53.411c-13.506 31.806-13.522 68.128 0 99.974L84.72 359.398c-38.319-63.217-38.319-143.579 0-206.796z"/>
                            </svg>
                        </span>
                        <?php echo __('Help'); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
