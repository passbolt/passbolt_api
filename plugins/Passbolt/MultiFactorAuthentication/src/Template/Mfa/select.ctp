<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication');
    $this->assign('title', $title);
    $version = Configure::read('passbolt.version');
    $themePath = "themes/$theme/api_main.min.css?v=$version";
    $this->Html->css($themePath, ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'iframe mfa');

    $mfaPossible = false;
    foreach($body[MfaSettings::ORG_SETTINGS] as $provider => $enabled) {
        if ($enabled) {
            $mfaPossible = true;
            break;
        }
    }
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col12 last">
            <h3><?= $title; ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col7">
            <?php if (!$mfaPossible): ?>
            <h4><?= __('Sorry no multi factor authentication is enabled for this organization.'); ?></h4>
            <p><?= __('Please contact your administrator to enable MFA.'); ?></p>
            <?php else: ?>
            <h4><?= __('Please select a provider'); ?></h4>
            <ul class="mfa-providers">
                <?php if ($body[MfaSettings::ORG_SETTINGS]['totp']) : ?>
                <li>

                    <?php $start = !$body[MfaSettings::ACCOUNT_SETTINGS]['totp'] ? 'start' : ''; ?>
                    <a href="<?= Router::url("/mfa/setup/totp/$start", true); ?>">
                        <img src="<?= Router::url('/img/third_party/google-authenticator.svg', true); ?>" />
                        <span>Google Authenticator</span>
                    </a>
                    <?php if ($body[MfaSettings::ACCOUNT_SETTINGS]['totp']) : ?>
                    <div class="mfa-provider-status enabled">
                        Enabled
                    </div>
                    <?php else: ?>
                    <div class="mfa-provider-status disabled">
                        Disabled
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
                        Enabled
                    </div>
                    <?php else: ?>
                    <div class="mfa-provider-status disabled">
                        Disabled
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
                        Enabled
                    </div>
                    <?php else: ?>
                    <div class="mfa-provider-status disabled">
                        Disabled
                    </div>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="col4 last">
            <h4><?= __('What is multi-factor authentication?'); ?></h4>
            <div class="message notice">
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
