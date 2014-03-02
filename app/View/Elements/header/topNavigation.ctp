<?php
/**
 * Top Navigation
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.topNavigation
 * @since         version 2.12.9
 */
  if(User::isGuest()) {
		$menu = array(
			'home' => array('url' => '#', 'class' => 'home'),
			'download' => array( 'url' => '#', 'class' => 'left'),
			'about' => array( 'url' => '#', 'class' => 'left' ),
			'login' => array( 'url' => '#', 'class' => 'right' )
		);
	} else {
		$menu = array(
			'home' => array( 'url' => '#', 'class' => 'home' ),
			'passwords' => array( 'url' => '#', 'class' => 'left'),
			'people' => array( 'url' => '#', 'class' => 'left' ),
			'help' => array( 'url' => '#', 'class' => 'left')
		);
	}
?>
  <nav>
  <div class="top navigation primary">
    <ul>
<?php foreach($menu as $name => $link) : ?>
      <li class="<?php echo $link['class'] ?>"><a href="<?php echo $link['url'] ?>"><span><?php echo $name; ?></span></a></li>
<?php endforeach; ?>
    </ul>
  </div>
  </nav>
