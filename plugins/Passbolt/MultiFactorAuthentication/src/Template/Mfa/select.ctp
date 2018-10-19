<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    $title = __('Multi factor authentication');
    $this->assign('title', $title);
    $version = Configure::read('passbolt.version');
    $themePath = "themes/$theme/api_main.min.css?v=$version";
    $this->Html->css($themePath, ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'iframe mfa');

    $mfaPossible = false;
    foreach($body['MfaOrganizationSettings'] as $provider => $enabled) {
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
            <h4><?= __('Please select a provider'); ?></h4>
            <ul class="mfa-providers">
                <?php if (!$mfaPossible): ?>
                <li>
                    <p><?= __('Sorry no multi factor authentication are enabled for this organization.'); ?></p>
                </li>
                <?php endif; ?>
                <?php if ($body['MfaOrganizationSettings']['totp']) : ?>
                <li>

                    <?php $start = !$body['MfaAccountSettings']['totp'] ? 'start' : ''; ?>
                    <a href="<?= Router::url("/mfa/setup/totp/$start", true); ?>">
                        <img src="<?= Router::url('img/third_party/google-authenticator.svg', true); ?>" />
                        <span>Google Authenticator</span>
                    </a>
                    <div class="input toggle-switch">
                    <?php if ($body['MfaAccountSettings']['totp']) : ?>
                        <label for="ga_switch"><?= __('Enabled'); ?></label>
                        <input class="toggle-switch-checkbox checkbox" id="ga_switch" type="checkbox" checked="checked" disabled="disabled">
                    <?php else: ?>
                        <label for="ga_switch"><?= __('Disabled'); ?></label>
                        <input class="toggle-switch-checkbox checkbox" id="ga_switch" type="checkbox" disabled="disabled">
                    <?php endif; ?>
                        <label class="toggle-switch-button" for="ga_switch"></label>
                    </div>
                </li>
                <?php endif; ?>
                <?php if ($body['MfaOrganizationSettings']['duo']) : ?>
                <li>
                    <a role="button" href="<?= Router::url('/mfa/setup/duo', true); ?>">
                        <img src="<?= Router::url('img/third_party/duo.svg', true); ?>" />
                        <span>Duo MFA</span>
                    </a>
                    <div class="input toggle-switch">
                        <?php if ($body['MfaAccountSettings']['duo']) : ?>
                            <label for="ga_switch"><?= __('Enabled'); ?></label>
                            <input class="toggle-switch-checkbox checkbox" id="duo_switch" type="checkbox" checked="checked" disabled="disabled">
                        <?php else: ?>
                            <label for="ga_switch"><?= __('Disabled'); ?></label>
                            <input class="toggle-switch-checkbox checkbox" id="duo_switch" type="checkbox" disabled="disabled">
                        <?php endif; ?>
                        <label class="toggle-switch-button" for="duo_switch"></label>
                    </div>
                </li>
                <?php endif; ?>
                <?php if ($body['MfaOrganizationSettings']['yubikey']) : ?>
                <li>
                    <a role="button" href="<?= Router::url('/mfa/setup/yubikey', true); ?>">
                        <img src="<?= Router::url('img/third_party/yubikey.svg', true); ?>" />
                        <span>Yubikey OTP</span>
                    </a>
                    <div class="input toggle-switch">
                        <?php if ($body['MfaAccountSettings']['yubikey']) : ?>
                            <label for="ga_switch"><?= __('Enabled'); ?></label>
                            <input class="toggle-switch-checkbox checkbox" id="yubikey_switch" type="checkbox" checked="checked"  disabled="disabled">
                        <?php else: ?>
                            <label for="ga_switch"><?= __('Disabled'); ?></label>
                            <input class="toggle-switch-checkbox checkbox" id="yubikey_switch" type="checkbox" disabled="disabled">
                        <?php endif; ?>
                        <label class="toggle-switch-button" for="yubikey_switch"></label>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
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