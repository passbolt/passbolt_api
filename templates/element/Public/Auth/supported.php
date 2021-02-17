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
$browser = strtolower($userAgent['Browser']['name']);
?>
<div class="col6 push1 information">
    <h2><?php echo __('Download the plugin to get started!'); ?></h2>
    <div class="plugin-check-wrapper">
        <div class="plugin-check <?= $browser ?> error">
            <p class="message">
                <?php echo __('An add-on is required to use passbolt.'); ?><br>
                <a href="https://www.passbolt.com/download/<?= $browser ?>">
                    <?php echo __('Download it here'); ?>
                </a>.
            </p>
        </div>
    </div>
    <p>
        <?php echo __('Passbolt is a simple password manager that allows you to easily share secrets 
        with your team without making compromises on security.'); ?>
    </p>
</div>
<div class="col4 push1 last">
    <div class="logo">
        <h1><span>Passbolt</span></h1>
    </div>
    <div class="users login form">
        <div class="feedback">
            <i class="fa fa-download huge" ></i>
        </div>
        <div class="actions-wrapper center">
            <a class="button primary big" href="https://www.passbolt.com/download/<?= $browser ?>">
                <?php echo __('Download the plugin'); ?>
            </a>
        </div>
    </div>
</div>
