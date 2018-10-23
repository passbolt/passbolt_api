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
?>
<div class="grid grid-responsive-12">
    <script type="text/javascript" src="<?= Router::url('js/app/Duo-Web-v2.js', true); ?>"></script>
    <iframe id="duo_iframe"
            data-host="<?= $hostName; ?>"
            data-sig-request="<?= $sigRequest; ?>"
    ></iframe>
    <?= $this->form->create($setupForm, ['id' => 'duo_form']); ?><?= $this->form->end(); ?>
</div>
