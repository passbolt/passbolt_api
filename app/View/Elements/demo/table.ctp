<?php
/**
 * Table Template Demo
 *
 * @copyright     copyright 2013 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.demo.table
 * @since         version 2.13.02
 */
  require(CAKE . 'TestSuite/Fixture/CakeTestFixture.php');
  require(APP . 'Test/Fixture/ResourceFixture.php');
  $secrets = new ResourceFixture();
	$secrets = $secrets->records;
?>
	<table>
	<thead>
		<tr>
			<th class="selections s-cell">
				<div class="checkbox"><input type="checkbox" name="select all" value="60d77ffa-7278-41fc-a4bb-1b63d7a10fce"></div></th>
			<th class="favorites">
				<a href="#">
					<i href="icon fav"></i>
					<span>fav all</span>
				</a>
			</th>
			<th class="resource m-cell"><a href="#"><span>Resource</span></a></th>
			<th class="username m-cell"><a href="#"><span>Username</span></a></th>
			<th class="uri l-cell"><a href="#"><span>URI</span></a></th>
			<th class="modified m-cell"><a href="#" class="sort desc"><span>Modified</span></a></th>
			<th class="expire m-cell"><a href="#"><span>Expires</span></a></th>
			<th class="owner m-cell"><a href="#"><span>Owner</span></a></th>
			<!--th class="controls"><a href="#"><span>Controls</span></a></th-->
		</tr>
	</thead>
	<tbody>
<?php foreach($secrets as $secret): ?>
		<tr>
			<td class="selections s-cell">
				<div class="checkbox"><input type="checkbox" name="select" value="50d77ffa-7278-41fc-a4bb-1b63d7a10fce"></div>
			</td>
			<!--td class="favorites"><a href="#"><span>fav all</span></a></td-->
			<td class="resource m-cell"><span><?php echo $secret['name']; ?></span></td>
			<td class="username m-cell"><a href="#"><span>Remy</span></a></td>
			<td class="uri l-cell"><a href="#"><span><?php echo $secret['uri']; ?></span></a></td>
			<td class="modified m-cell"><span><?php echo $secret['modified']; ?></span></td>
			<td class="expire m-cell"><span><?php echo $secret['modified']; ?></span></td>
			<td class="owner m-cell"><a href="#"><span>Kevin</span></a></td>
			<!--td class="controls"><a href="#"><span>Controls</span></a></td-->
		</tr>
<?php endforeach; ?>
	</tbody>
	</table>
