<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';
$this->assign('title', $message);
$this->assign('pageClass', 'error-404');
?>
<div class="grid">
    <div class="row">
        <h2><?= h($message) ?></h2>
        <p class="error">
            <?= __('The requested address was not found on this server.') ?>
            <?= __('Please double check the url.') ?>
            <?= __('Maybe the page was deleted or moved.') ?>
        </p>
    </div>
<?php if (Configure::read('debug')): ?>
    <div class="row">
    <?= $this->element('exception_stack_trace'); ?>
    <?php if (!empty($error->queryString)) : ?>
        <p class="notice">
            <strong>SQL Query: </strong>
            <?= h($error->queryString) ?>
        </p>
    <?php endif; ?>
    <?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
    <?php Debugger::dump($error->params) ?>
    <?php endif; ?>
        <?= $this->element('auto_table_warning') ?>
        <?php if (extension_loaded('xdebug')): xdebug_print_function_stack(); endif; ?>
    </div>
<?php endif;?>
</div>
