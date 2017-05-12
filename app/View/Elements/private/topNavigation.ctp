<?php
/**
 * Top Navigation
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$menu = array(
	'passbolt' => array('url' => Router::url('/'), 'class' => 'home with-link'),
	'passwords' => array( 'url' => Router::url('/#'), 'class' => 'left'),
	'logout' => array( 'url' => Router::url('/logout'), 'class' => 'right' )
);
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
