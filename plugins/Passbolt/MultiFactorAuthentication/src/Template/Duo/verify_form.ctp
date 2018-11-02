<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->Html->css('themes/anew/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'login');
    $this->Html->css('Duo-Frame.css', ['block' => 'css', 'fullBase' => true]);
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
    <?= $this->form->create($verifyForm, ['id' => 'duo_form']); ?><?= $this->form->end(); ?>
    <?= $this->element('formActions', ['providers' => $providers, 'currentProvider' => MfaSettings::PROVIDER_DUO]); ?>
</div>
