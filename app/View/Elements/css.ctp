<?php
/**
 * CSS Elements
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.css
 * @since         version 2.12.6
 */
  $css = array('reset','grid','font','colors','icons','form','buttons','navigation','popup','tree','breadcrumb','table','footer');
  if (Configure::read('debug') > 1) {
    $css[] = 'debug';
  }
  if (!User::isAnonymous()) {
    $css = array_merge($css,array('notificator','header','search','workspace.password','menu.dropdown'));
  }
?>
<!--  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato" />-->
<?php foreach($css as $s) : ?>
  <link rel="stylesheet" type="text/css" href="css/<?php echo $s; ?>.css" />
<?php endforeach; ?>
  <?php echo $this->fetch('css'); ?>
