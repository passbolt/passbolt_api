<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $setupForm
 * @var string $formUrl
 * @var array $body
 */
    use Cake\Core\Configure;

    $version = Configure::read('passbolt.version');
    $title = __('Duo Multi Factor Authentication');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $this->Html->script(
        "app/mfa-settings.js?v=$version",
        ['block' => 'js', 'fullBase' => true]
    );
    $formContext = [
        'url' => $formUrl,
        'target' => '_top',
        'id' => 'duo_form',
    ];
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <div class="feedback-card">
                <?= $this->element('failedMark'); ?>
                <div class="additional-information">
                    <p><?= __('Something went wrong.'); ?><br/><?= __('Please try again later or contact your administrator.'); ?></p>

                    <h4 class="logs-header"><?= __('Logs'); ?></h4>
                    <textarea><?= $this->Flash->render('duo_auth_error') ?></textarea>
                    <?= $this->Form->create($setupForm, $formContext); ?>
                            <button type="submit" class="button primary"><?= __('Retry'); ?></button>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
            <?= $this->element('manageProvidersButton'); ?>
        </div>
    </div>
</div>
