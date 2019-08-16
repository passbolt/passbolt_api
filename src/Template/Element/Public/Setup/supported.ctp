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
?>
<div class="plugin-check-wrapper">
    <h3><?php echo __('Plugin check') ?></h3>
    <div class="plugin-check <?= $browserName; ?> error">
        <p class="message">
            <?php echo __('A web extension is required to use passbolt.'); ?><br>
            <a href="https://www.passbolt.com/download/<?= $browserName; ?>" target="_blank" rel="noopener">
                <?php echo __('Download it here'); ?>
            </a>.
        </p>
    </div>
</div>
<div class="why-plugin-wrapper">
    <h3><?php echo("Why do I need a plugin"); ?></h3>
    <p>
        <?php echo __('Passbolt requires a browser add-on to guarantee that your secret key and your passphrase are never
			accessible to any website (including passbolt.com itself). This is also the only way to guarantee that
			the core cryptographic libraries cannot be tampered with.'); ?>
    </p>
</div>
<div class="submit-input-wrapper">
    <a id="js_setup_plugin_check" class="button primary big">retry</a>
</div>
