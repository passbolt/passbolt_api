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
<div class="tableview">
<div class="tableview-header">
	<div class="breadcrumbs">
		<ul>
			<li><a href="#">home</a></li>
			<li><a href="#">category 1</a></li>
			<li><a href="#">another one</a></li>
		</ul>
	</div>
	<table>
		<thead>
			<tr>
				<th class="selections s-cell">
					<div class="input checkbox">
						<input type="checkbox" name="select all" value="checkbox-select-all" id="checkbox-select-all">
						<label for="checkbox-select-all">select all</label>
					</div>
				</th>
				<td class="favorites s-cell">
					<a href="#">
						<i class="icon fav no-text"></i>
						<span>fav</span>
					</a>
				</td>
				<th class="resource m-cell"><a href="#"><span>Resource</span></a></th>
				<th class="username m-cell"><a href="#"><span>Username</span></a></th>
				<th class="password l-cell"><a href="#"><span>Password</span></a></th>
				<th class="uri l-cell"><a href="#"><span>URI</span></a></th>
				<th class="modified m-cell"><a href="#" class="sort desc"><span>Modified</span></a></th>
				<th class="expire m-cell"><a href="#"><span>Expires</span></a></th>
				<th class="owner m-cell"><a href="#"><span>Owner</span></a></th>
				<!--th class="controls"><a href="#"><span>Controls</span></a></th-->
			</tr>
		</thead>
	</table>
</div>
<div class="tableview-content scroll">
	<table>
		<tbody>
<?php $i = 0; foreach($secrets as $secret): ++$i;?>
			<tr>
				<td class="selections s-cell">
					<div class="input checkbox">
						<input type="checkbox" name="select" id="checkbox-<?php echo $secret['id']; ?>">
						<label for="checkbox-<?php echo $secret['id']; ?>">select <?php echo $secret['name']; ?></label>
					</div>
				</td>
				<td class="favorites s-cell">
					<a href="#">
						<i class="icon no-text <?php if($i%5) echo 'fav'; else echo 'unfav';?>"></i>
						<span><?php if($i%5) echo 'fav'; else echo 'unfav';?></span>
					</a>
				</td>
				<td class="resource m-cell"><span><?php echo $secret['name']; ?></span></td>
				<td class="username m-cell"><a href="#"><span>Splashy</span></a></td>
				<td class="password l-cell"><span>
<pre>
-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA2ZKzHOmbpbCAQf9G03BHUC173Krctq880HDdvxdea7UvKXPPImhDhM2tPM5
ASA+Dk1nGXtSU5+TXSFEZmcF9pEw05aV47Tcg022ZXItOl/wq0Pl8YU2r0yiwWEk
uc5sOChnirE04Vy+cKnGBJe9yIrUsW4WZnA9G7644r8wVhBpRYLq0YyueLzfmb9d
sJo1jbiZzkoi/FLuCiQWFlCRZnJXgrF0UTQuN+bT3cOXmIVbT1tHrQoidD72nVer
idlluPJKCLvFmaBW/rvqoQjebJV3c74RmYVClpjXEEP5cd4XfvEDUuOBU4NdwylB
JGHt0fMslxMKfip1YOTIQmC4etXIXC6zkM/xxRAbJNJPAdiIev4q1aipMm/oRmcA
k1uvyQ2F13QXWCLNcZICCAiQTrPeMFbtnhky0940M4Dcg6LfPNIS0xEEVqzONq4F
dK6Jg4eWnZXcj7+8Acf43Q==
=Sh3E
-----END PGP MESSAGE-----
</pre>
				</span></td>
				<td class="uri l-cell"><a href="#"><span><?php echo $secret['uri']; ?></span></a></td>
				<td class="modified m-cell"><span><?php echo $secret['modified']; ?></span></td>
				<td class="expire m-cell"><span><?php echo $secret['modified']; ?></span></td>
				<td class="owner m-cell"><a href="#"><span>Kevin</span></a></td>
				<!--td class="controls"><a href="#"><span>Controls</span></a></td-->
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>
