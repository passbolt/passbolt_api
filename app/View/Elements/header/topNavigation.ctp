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
			'home' => array(
				'url' => '#', 'class' => 'home', 'wrapper' => true
			),
			'discover' => array(
				'url' => '#', 'class' => 'left'
			),
			'pricing' => array(
				'url' => '#', 'class' => 'left'
			),
			'register' => array(
				'url' => '#', 'class' => 'left'
			),
			'faq' => array(
				'url' => '#', 'class' => 'left'
			),
			'help' => array(
				'url' => '#', 'class' => 'left'
			)
		);
	} else {
		$menu = array(
			'home' => array(
				'url' => '#', 'class' => 'home', 'wrapper' => true
			),
			'passwords' => array(
				'url' => '#', 'class' => 'left'
			),
			'people' => array(
				'url' => '#', 'class' => 'left'
			),
			'help' => array(
				'url' => '#', 'class' => 'left'
			)
		);
	}
?>
  <nav>
  <div class="top navigation">
    <ul>
<?php foreach($menu as $name => $link) : ?>
      <li><a href="<?php echo $link['url'] ?>" class="<?php echo $link['class'] ?>"><?php if(isset($link['wrapper']) && $link['wrapper'])  : ?><span><?php echo $name; ?></span><?php else: echo $name; endif; ?></a></li>
<?php endforeach; ?>
    </ul>
  </div>
  </nav>
