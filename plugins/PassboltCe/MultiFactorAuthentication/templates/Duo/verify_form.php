<?php
/**
 * @var \App\View\AppView $this
 * @var string $hostName
 * @var mixed $providers
 * @var string $sigRequest
 * @var mixed $verifyForm
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login');
    $this->Html->css('Duo-Frame.css', ['block' => 'css', 'fullBase' => true]);
    $formContext = [
        'url' => Router::url('/mfa/verify/duo?redirect=' . $redirect, true),
        'id' => 'duo_form',
    ];
    ?>
<div class="login-form ">
    <h1>
        <?= $title; ?>
    </h1>
    <script type="text/javascript" src="<?= Router::url('js/app/Duo-Web-v2.js', true); ?>"></script>
    <iframe id="duo_iframe"
            data-host="<?= $hostName; ?>"
            data-sig-request="<?= $sigRequest; ?>"
    ></iframe>
    <?= $this->Form->create($verifyForm, $formContext); ?><?= $this->Form->end(); ?>
    <?= $this->element('formActions', ['providers' => $providers, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
</div>
