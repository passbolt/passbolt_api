<?php
/**
 * @var \App\View\AppView $this
 * @var string $hostName
 * @var mixed $providers
 * @var mixed $verifyForm
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login');
    $formContext = [
        'url' => Router::url('/mfa/verify/duo?redirect=' . $redirect, true),
        'id' => 'duo_form',
    ];
    ?>
<div class="login-form ">
    <h1>
        <?= $title; ?>
    </h1>
    <?= $this->Form->create($verifyForm, $formContext); ?><?= $this->Form->end(); ?>
    <?= $this->element('formActions', ['providers' => $providers, 'redirect' => $redirect, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
</div>
