<?php
/**
 * CSS Elements
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.css
 * @since         version 2.12.6
 */
$css = array('default/aa1d4fae-7dec-11d0-a765-00a0c91e6bf6');
  if (Configure::read('debug') > 1) {
    $css[] = 'debug';
  }
  if (!User::isAnonymous()) {
    // $css = array_merge($css,array('notificator','header','search','workspace.password','menu.dropdown'));
  }
?>
<!--  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato" />-->
<?php foreach($css as $s) : ?>
  <link rel="stylesheet" type="text/css" href="css/<?php echo $s; ?>.css" />
<?php endforeach; ?>
  <?php echo $this->fetch('css'); ?>
