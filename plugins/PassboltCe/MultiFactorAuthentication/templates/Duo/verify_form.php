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
    <h1>
        <?= __('Duo Multi Factor Authentication'); ?>
    </h1>
    <?= $this->Flash->render() ?>
    <?= $this->Form->create($verifyForm, $formContext); ?>
        <button type="submit" class="button primary big full-width" style="margin-top:3.2rem;" role="button"><?= __('Sign-in with Duo'); ?></button>
        <?= $this->element('formActions', ['providers' => $providers, 'redirect' => $redirect, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
    <?= $this->Form->end(); ?>
</div>
