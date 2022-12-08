<?php
/**
 * @var \App\View\AppView $this
 * @var string $hostName
 * @var mixed $setupForm
 * @var string $sigRequest
 */
    use Cake\Core\Configure;
    use Cake\Routing\Router;

    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $this->Html->css('Duo-Frame.css', ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/duo', true),
        'id' => 'duo_form',
    ];
    ?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <script type="text/javascript" src="<?= Router::url('js/app/Duo-Web-v2.js', true); ?>"></script>
            <iframe id="duo_iframe"
                    data-host="<?= $hostName; ?>"
                    data-sig-request="<?= $sigRequest; ?>"
            ></iframe>
            <?= $this->Form->create($setupForm, $formContext); ?><?= $this->Form->end(); ?>
            <div class="actions-wrapper">
                <a href="<?= Router::url('/mfa/setup/select', true); ?>" class="button cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>
