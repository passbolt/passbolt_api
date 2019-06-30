<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;

    $title = __('Duo multi-factor authentication');
    $this->assign('title', $title);
    $version = Configure::read('passbolt.version');
    $themePath = "themes/$theme/api_main.min.css?v=$version";
    $this->Html->css($themePath, ['block' => 'css', 'fullBase' => true]);
    $this->Html->css('Duo-Frame.css', ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/duo', true),
        'id' => 'duo_form'
    ];
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col12 last">
            <h3><?= $title; ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col7">
            <script type="text/javascript" src="<?= Router::url('js/app/Duo-Web-v2.js', true); ?>"></script>
            <iframe id="duo_iframe"
                    data-host="<?= $hostName; ?>"
                    data-sig-request="<?= $sigRequest; ?>"
            ></iframe>
            <?= $this->form->create($setupForm, $formContext); ?><?= $this->form->end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col7 last">
            <div class="actions-wrapper">
                <div class="actions-wrapper">
                    <a href="<?= Router::url('/mfa/setup/select', true); ?>" class="button cancel">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
