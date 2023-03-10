<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $providers
 * @var mixed $verifyForm
 * @var string $formUrl
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login');
    $formContext = [
        'url' => $formUrl,
        'target' => '_top',
        'id' => 'duo_form',
    ];
    ?>

<div class="login-form ">
    <img src="<?= Router::url('/img/third_party/duo.svg', true); ?>" class="centered-login-provider-icon" />
    <h1 class="centered-text login-title">
        <?= __('Multi Factor Authentication Required'); ?>
    </h1>
    <?= $this->Flash->render() ?>
    <p class="centered-text">
        <?= __('An additional authentication is required using Duo. You will be redirected to Duo for verification.'); ?>
    </p>
    <?= $this->Form->create($verifyForm, $formContext); ?>
        <?= $this->element('formActions', ['providers' => $providers, 'redirect' => $redirect, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
    <?= $this->Form->end(); ?>
</div>
