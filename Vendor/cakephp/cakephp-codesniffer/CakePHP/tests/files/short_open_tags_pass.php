<?php
/**
 * If shortopen tags are not enabled phpcs will report that the file has no php code
 * Ensure that there is some php code to skip that logic
 */
$ensure = 'some php code'; ?>

<?= "this should pass";
