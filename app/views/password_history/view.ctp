<div id="window-changes-history">
<h2>Changes history</h2>
<div class="facebox-container">
<table>
	<thead>
		<tr><td>id</td><td>date</td><td>password</td></tr>
	</thead>
	<tbody>
		
<?php
$i = 0;
foreach($history as $h):
?>
	<tr><td><?php echo $i; ?></td><td><?php echo $h['PasswordHistory']['created'] ?></td><td><?php echo $h['PasswordHistory']['value'] ?></td></tr>
<?php
	$i++;
endforeach;
?>
	</tbody>
</table>
</div>
</div>