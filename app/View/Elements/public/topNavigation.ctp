<?php
/**
 * Top Navigation
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
	$menu = array(
		'passbolt' => array('url' => Router::url('/'), 'class' => 'home with-link'),
		'home' => array( 'url' => Router::url('/'), 'class' => 'left'),
		'login' => array( 'url' => Router::url('/login'), 'class' => 'right' )
	);
	if (Configure::read('App.registration.public')) {
		$menu['register'] = array( 'url' => Router::url('/register'), 'class' => 'right' );
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
