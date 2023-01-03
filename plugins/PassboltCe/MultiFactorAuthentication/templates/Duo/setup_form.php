<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $setupForm
 */
    use Cake\Routing\Router;

    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/duo/prompt', true),
        'target' => '_top',
        'id' => 'duo_form',
    ];
    ?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <?= $this->Form->create($setupForm, $formContext); ?>
                <div class="actions-wrapper">
                    <a href="<?= Router::url('/mfa/setup/select', true); ?>" class="button cancel">Cancel</a>
                    <button type="submit" class="button primary">Sign-in with Duo</button>
                </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
