<?php
/**
 * Top Navigation
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
  if(User::isGuest()) {
		$menu = array(
			'passbolt' => array('url' => 'https://www.passbolt.com', 'class' => 'home with-link'),
			'home' => array( 'url' => '/', 'class' => 'left'),
//			'about' => array( 'url' => '#', 'class' => 'left' ),
			'login' => array( 'url' => 'login', 'class' => 'right' )
		);
	    if (Configure::read('Registration.public')) {
		    $menu['register'] = array( 'url' => 'register', 'class' => 'right' );
	    }
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
