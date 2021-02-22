<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->Html->css('themes/default/api_authentication.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'login');
    $this->Html->css('Duo-Frame.css', ['block' => 'css', 'fullBase' => true]);
    $formContext = [
        'url' => Router::url('/mfa/verify/duo', true),
        'id' => 'duo_form'
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
    <?= $this->form->create($verifyForm, $formContext); ?><?= $this->form->end(); ?>
    <?= $this->element('formActions', ['providers' => $providers, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
</div>
