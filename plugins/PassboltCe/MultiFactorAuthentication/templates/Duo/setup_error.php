<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $setupForm
 * @var array $body
 */
    use Cake\Core\Configure;
use Cake\Routing\Router;

    $version = Configure::read('passbolt.version');
    $title = __('Duo Multi Factor Authentication');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $this->Html->script(
        "app/mfa-settings.js?v=$version",
        ['block' => 'js', 'fullBase' => true]
    );
    $formContext = [
        'url' => Router::url('/mfa/setup/duo/prompt?redirect=' . $redirect, true),
        'target' => '_top',
        'id' => 'duo_form',
    ];
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <div class="feedback-card">
                <?= $this->element('errorMark'); ?>
                <div style="padding:2rem; flex-grow:1">
                    <p><?= __('Something went wrong.'); ?><br/><?= __('Please try again later or contact your administrator.'); ?></p>
                    <p style="margin-top:2rem; margin-bottom:1rem; font-weight:bold; font-size:1.5rem"><?= __('Logs.'); ?></p>
                    <textarea><?= $this->Flash->render('duo_auth_error') ?></textarea>
                    <?= $this->Form->create($setupForm, $formContext); ?>
                        <div class="actions-wrapper" style="margin:0; margin-top:2rem">
                            <button type="submit" class="button primary"><?= __('Retry'); ?></button>
                        </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
            <?= $this->element('manageProvidersButton'); ?>
        </div>
    </div>
</div>
