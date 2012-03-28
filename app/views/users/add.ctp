<div id="window-user">
<h2>Add a user</h2>
<div class="facebox-container">
<?php
echo $this->Form->create("User");
?>
<div class="user">
<fieldset>
<legend>User Information</legend>
<input id="UserGroupsId" type="hidden" name="data[Group][group_id]" value="<?php //echo $group_id; ?>" />
<?
echo $this->Form->input('name', array('label' => 'Full name'));
echo $this->Form->input('email', array('label' => 'Email'));
echo $this->Form->input('username', array('label' => 'Username'));
echo $this->Form->input('password', array('label' => 'Password', 'autocomplete'=>'off'));
echo $this->Form->input('passwordRepeat', array('label' => 'Repeat', 'type'=>'password', 'autocomplete'=>'off'));
//echo $this->Form->textarea('address', array('label' => 'Address'));
?>
</fieldset>
</div>
<div class="groups-container">
	<fieldset>
	<legend>Privileges</legend>
	<div class="admin">
	<?php
		echo $this->Form->input('admin', array('label' => 'User is administrator'));
	?>
	<hr class="spacer" />
	</div>
	<em>or select a group</em>
	<div class="groups">
		<?php
			echo $this->Tree->generate($groups, array('element'=>'node_group', 'elementIdField'=>'id', 'elementRelField'=>'rel', 'model'=>'Group', 'depth'=>'30')); 
		?>
	</div>
	</fieldset>
</div>
<?php
echo $this->Form->submit('Save', array('class'=>'submit'));
echo $this->Form->end();
?>
<hr class="spacer" />
</div>
</div>