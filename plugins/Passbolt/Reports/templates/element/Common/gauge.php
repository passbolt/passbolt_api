<?php
/**
 * @var \App\View\AppView $this
 * @var string $radd
 * @var mixed $status
 * @var string $text
 * @var string $value
 */
use Passbolt\Reports\Utility\AbstractSingleReport;

$this->Html->script('vendors/jquery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('vendors/report-widgets.js', ['block' => 'scriptBottom']);
$this->Html->script('vendors/apexcharts.min.js', ['block' => 'scriptBottom']);

$statuses = [
    AbstractSingleReport::STATUS_SUCCESS => 'green',
    AbstractSingleReport::STATUS_FAIL => 'red',
    AbstractSingleReport::STATUS_IN_PROGRESS => 'orange',
];
?>
<div class="report-widget gauge">
    <div class="widget-content" data-value="<?= $value ?>" data-textradd="<?= $radd ?>" data-color="<?= $statuses[$status] ?>"></div>
    <?php if (isset($text)) : ?>
    <p class="widget-description"><?= $text ?></p>
    <?php endif; ?>
</div>