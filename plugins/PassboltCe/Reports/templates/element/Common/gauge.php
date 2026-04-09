<?php
/**
 * @var \App\View\AppView $this
 * @var string $radd
 * @var mixed $status
 * @var string $text
 * @var string $value
 */
use Passbolt\Reports\Utility\AbstractSingleReport;

// apexcharts.min.js and report-widgets.js dependencies were dropped — the report plugin is not functional.

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