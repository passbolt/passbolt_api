<?php
/**
 * @var \App\View\AppView $this
 * @var array $body
 */
    use Cake\Core\Configure;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $version = Configure::read('passbolt.version');
    $title = __('Duo multi-factor authentication is enabled!');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $this->Html->script(
        "app/mfa-settings.js?v=$version",
        ['block' => 'js', 'fullBase' => true]
    );
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <div class="feedback-card">
                <?= $this->element('successMark'); ?>
                <div class="additional-information">
                    <p>
                        <?= __('When logging in you will be asked to perform Duo Authentication.'); ?>
                    </p>
                    <p class="created date">
                        <?= __('Since')?>: <?= $body['verified']->nice(); ?>
                    </p>
                    <?= $this->element('turnOffProviderButton', ['provider' => MfaSettings::PROVIDER_DUO]); ?>
                </div>
            </div>
            <?= $this->element('manageProvidersButton'); ?>
        </div>
    </div>
</div>
