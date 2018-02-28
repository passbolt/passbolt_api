<?php
$this->Html->css('setup.min', ['block' => 'css']);
$this->assign('page_classes', 'setup install start');
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
        <div class="logo no-img">
            <h1><span>Passbolt</span></h1>
        </div>
    </div>
    <div class="col2_3">
        <h2 id="js_step_title"><?= $title; ?></h2>
    </div>
</div>