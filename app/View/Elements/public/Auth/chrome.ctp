<?php
/**
 * Login first step
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<div class="col6 push1 information">
    <h2>
        <?php echo __('Download the plugin to get started!'); ?>
    </h2>
    <div class="plugin-check-wrapper">
		<?php echo $this->element('public/plugin/chrome-no-addon'); ?>
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
            <a class="button primary big" href="https://www.passbolt.com/download/chrome">
                <?php echo __('Download the plugin'); ?>
            </a>
        </div>
    </div>
</div>