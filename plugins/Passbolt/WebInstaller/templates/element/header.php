<?php
use Cake\Core\Configure;

$this->Html->css('themes/default/api_webinstaller.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
$this->assign('page_classes', 'setup install');
?>
<!-- first header -->
<div class="header first">
    <nav>
        <div class="primary navigation top">
            <!-- no top links at setup -->
        </div>
    </nav>
</div>

<!-- second header -->
<div class="header second">
    <div class="col1">
        <div class="logo no-img"></div>
    </div>
    <div class="col2_3">
        <h1 id="js_step_title"><?= $title; ?></h1>
    </div>
</div>
