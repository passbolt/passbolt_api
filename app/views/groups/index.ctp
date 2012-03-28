<div id="groups-management-window">
	<h2>Manage Groups</h2>
	<div class="groups span-16">
		<div id="groups-management" class="jstree">
			<?php
				echo $this->Tree->generate($groups, array('element'=>'node_group', 'elementIdField'=>'id', 'elementRelField'=>'rel','model'=>'Group', 'depth'=>'30')); 
			?>
		</div>
		<a href="/groups/create" id="add_group">Add group</a>
		</div>
	<div class="help span-7">
		To rename, delete and create groups, use the right click of your mouse.
	</div>
	<hr class="spacer" />
</div>