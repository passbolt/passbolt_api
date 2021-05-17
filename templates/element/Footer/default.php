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
$version = Configure::read('passbolt.version');
$privacyUrl = Configure::read('passbolt.legal.privacy_policy.url');
$termsUrl = Configure::read('passbolt.legal.terms.url');
?>
<footer>
    <div class="footer">
        <ul class="footer-links">
<?php if (isset($safeMode) && !$safeMode) : ?>
            <li class="error message"><a href="https://help.passbolt.com/faq/hosting/why-unsafe" title="terms of service">Unsafe mode</a></li>
<?php endif; ?>
<?php if (!empty($termsUrl)) : ?>
            <li><a href="<?php echo $termsUrl ?>"><?= __('Terms'); ?></a></li>
<?php endif; ?>
<?php if (!empty($privacyUrl)) : ?>
            <li><a href="<?php echo $privacyUrl ?>"><?= __('Privacy'); ?></a></li>
<?php endif; ?>
            <li><a href="https://www.passbolt.com/credits"><?= __('Credits'); ?></a></li>
            <li id="version">
                <a href="https://www.passbolt.com/credits" class="tooltip-left" data-tooltip="<?= $version; ?>">
                    <i class="fa fa-heart-o"></i>
                    <span class="visuallyhidden"><?= __('Versions'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</footer>
