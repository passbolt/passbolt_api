<?php
/**
 * @var \App\View\AppView $this
 * @var string $hostName
 * @var mixed $providers
 * @var mixed $verifyForm
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login');
    $formContext = [
        'url' => Router::url('/mfa/verify/duo/prompt?redirect=' . $redirect, true),
        'target' => '_top',
        'id' => 'duo_form',
    ];
    ?>

<div class="login-form ">
    <img src="<?= Router::url('/img/third_party/duo.svg', true); ?>" class="centered-login-provider-icon" />
    <h1 class="centered-text login-title">
        <?= __('Multi Factor Authentication Required'); ?>
    </h1>
    <p class="centered-text">
        <?= __('An additional authentication is required using Duo. You will be redirected to Duo for verification.'); ?>
    </p>
    <?= $this->Flash->render() ?>
    <?= $this->Form->create($verifyForm, $formContext); ?>
        <?= $this->element('formActions', ['providers' => $providers, 'redirect' => $redirect, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
    <?= $this->Form->end(); ?>
</div>
