<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';
$this->assign('pageClass', 'error-500');
$this->assign('title', $message);
?>
<div class="grid">
    <div class="row">
        <h2><?= __d('cake', 'An Internal Error Has Occurred') ?></h2>
        <p class="error">
            <?= h($message) ?>
        </p>
    </div>
<?php if (Configure::read('debug')): ?>
    <div class="row" style="max-width:960px;padding:1em;margin-bottom:2em;background:#efefef;font-family: monospace;">
    <?php if (!empty($error->queryString)) : ?>
        <p class="notice">
            <strong>SQL Query: </strong>
            <pre><?= h($error->queryString) ?></pre>
        </p>
    <?php endif; ?>
    <?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
    <?php endif; ?>
    <?php if ($error instanceof Error) : ?>
        <strong>Error in: </strong>
        <pre>
        <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
    <?php endif; ?>
    <?php
        echo $this->element('auto_table_warning');
        if (extension_loaded('xdebug')): xdebug_print_function_stack(); endif;
    ?>
        </pre>
<?php endif; ?>
    </div>
</div>
