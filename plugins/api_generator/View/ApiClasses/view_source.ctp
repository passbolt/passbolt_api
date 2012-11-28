<?php
/**
 * View the source code for a file.
 *
 */
$this->set('title_for_layout', $this->ApiDoc->trimFileName($filename));
?>
<h1><?php echo $this->ApiDoc->trimFileName($filename); ?></h1>

<?php echo $this->ApiUtils->highlight($contents); ?>
