<?php
/**
 * Login first step
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<div class="col6 push1 information">
    <h2>
        <?php echo __('Download the plugin to get started!'); ?>
    </h2>
    <div class="plugin-check-wrapper">
        <div class="plugin-check firefox error">
            <p class="message">
                <?php echo __('An add-on is required to use Passbolt. Download it at: '); ?>
                <a href="https://github.com/passbolt/passbolt_ff/raw/develop/passbolt-firefox-addon.xpi">addons.mozilla.org</a>.</p>
        </div>
    </div>
    <?php echo $this->element('public/teaser-text'); ?>
</div>
<div class="col4 push1 last">
    <?php echo $this->element('public/logo'); ?>
    <div class="users login form">
        <div class="feedback">
            <i class="fa fa-download huge" ></i>
        </div>
        <div class="actions-wrapper center">
            <a class="button primary big" href="https://github.com/passbolt/passbolt_ff/raw/develop/passbolt-firefox-addon.xpi">
                <?php echo __('Download the plugin'); ?>
            </a>
        </div>
    </div>
</div>