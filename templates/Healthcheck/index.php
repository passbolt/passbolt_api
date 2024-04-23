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
 *
 * @var array $body
 */

use App\View\Helper\HealthcheckHtmlHelper;
use Cake\Core\Configure;

$this->assign('title',	__('Health checks'));
$this->Html->css('themes/default/api_main.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'status');

$healthcheckHelper = new HealthcheckHtmlHelper();
?>

<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col8">
            <h1><?php echo __('Passbolt API Status') ?></h1>
            <?php
            foreach ($body as $domain => $checkResults) {
                echo '<h3>' . $domain . '</h3>';

                foreach ($checkResults as $checkResult) {
                    $healthcheckHelper->render($checkResult);
                }
            }
            ?>
            <!-- if the javascript does not load this message will be shown -->
            <div id="url-rewriting-warning" class="message error">
                <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                <a target="_blank" rel="noopener noreferrer" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
            </div>
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
                    <span class="svg-icon life-ring icon-only">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70966 13.43C11.0731 13.43 13.7996 10.7034 13.7996 7.34C13.7996 3.97659 11.0731 1.25 7.70966 1.25C4.34624 1.25 1.61969 3.97659 1.61969 7.34C1.61969 10.7034 4.34624 13.43 7.70966 13.43Z" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.70966 9.77999C9.05723 9.77999 10.1497 8.68757 10.1497 7.33999C10.1497 5.99242 9.05723 4.89999 7.70966 4.89999C6.36208 4.89999 5.26971 5.99242 5.26971 7.33999C5.26971 8.68757 6.36208 9.77999 7.70966 9.77999Z" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.40973 3.03L5.98969 5.62" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.43964 9.06L12.0197 11.65" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.43964 5.62L12.0197 3.03" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.43964 5.62L11.5897 3.47" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.40973 11.65L5.98969 9.06" fill="none" stroke="var(--icon-color)" vector-effect="non-scaling-stroke" stroke-width="var(--icon-stroke-width)" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>
                        <?php echo __('Help'); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
