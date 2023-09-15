<?php
/**
 * @var \App\View\AppView $this
 * @var array $body
 * @var bool $isMfaPossible
 */
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

$title = __('Multi factor authentication');
$this->assign('title', $title);
$this->assign('pageClass', 'iframe mfa');

?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <?php if (!$isMfaPossible) : ?>
            <h4 class="no-border"><?= __('Sorry no multi factor authentication is enabled for this organization.'); ?></h4>
            <p><?= __('Please contact your administrator to enable multi-factor authentication.'); ?></p>
            <?php else : ?>
            <h4 class="no-border"><?= __('Please select a provider'); ?></h4>
            <ul class="mfa-providers">
                <?php if ($body[MfaSettings::ORG_SETTINGS]['totp']) : ?>
                <li>
                    <?php $start = !$body[MfaSettings::ACCOUNT_SETTINGS]['totp'] ? 'start' : ''; ?>
                    <a href="<?= Router::url("/mfa/setup/totp/$start", true); ?>">
                        <img src="<?= Router::url('/img/third_party/google-authenticator.svg', true); ?>" />
                        <span>TOTP authenticator</span>
                    </a>
                    <?php if ($body[MfaSettings::ACCOUNT_SETTINGS]['totp']) : ?>
                    <div class="mfa-provider-status enabled">
                        <?= __('Enabled') ?>
                    </div>
                    <?php else : ?>
                    <div class="mfa-provider-status disabled">
                        <?= __('Disabled') ?>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
                <?php if ($body[MfaSettings::ORG_SETTINGS]['duo']) : ?>
                <li>
                    <a role="button" href="<?= Router::url('/mfa/setup/duo', true); ?>">
                        <img src="<?= Router::url('/img/third_party/duo.svg', true); ?>" />
                        <span>Duo MFA</span>
                    </a>
                    <?php if ($body[MfaSettings::ACCOUNT_SETTINGS]['duo']) : ?>
                    <div class="mfa-provider-status enabled">
                        <?= __('Enabled') ?>
                    </div>
                    <?php else : ?>
                    <div class="mfa-provider-status disabled">
                        <?= __('Disabled') ?>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
                <?php if ($body[MfaSettings::ORG_SETTINGS]['yubikey']) : ?>
                <li>
                    <a role="button" href="<?= Router::url('/mfa/setup/yubikey', true); ?>">
                        <img src="<?= Router::url('/img/third_party/yubikey.svg', true); ?>" />
                        <span>Yubikey OTP</span>
                    </a>
                    <?php if ($body[MfaSettings::ACCOUNT_SETTINGS]['yubikey']) : ?>
                    <div class="mfa-provider-status enabled">
                        <?= __('Enabled') ?>
                    </div>
                    <?php else : ?>
                    <div class="mfa-provider-status disabled">
                        <?= __('Disabled') ?>
                    </div>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="col4 last">
            <div class="sidebar-help">
                <h3><?= __('What is multi-factor authentication?'); ?></h3>
                <p>
                    <?= __('Multi-factor authentication (MFA) is a method of confirming a user\'s identity that requires presenting two or more pieces of evidence (or factor).'); ?>
                </p>
                <a class="button" href="https://help.passbolt.com/start/" target="_blank" rel="nofollow noopener">
                    <?= __('learn more'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
