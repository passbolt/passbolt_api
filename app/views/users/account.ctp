<div class="window-header">
	<h2>My Account</h2>
</div>

<div class="window-container account">
	<div class="user_photo">
	  <?php
	  	if(isset($this->data["User"]["avatar"]) && !empty($this->data["User"]["avatar"])){
	  ?>
	  <img src="/img/users/big/<?php  echo $this->data["User"]["avatar"]; ?>" width="150">
	  <?php
	  	}
		else{
	  ?>
	  	 <img src="/img/users/avatar.png" width="150">
	  <?php
	  	}
	  ?>
	  <a href="/users/accountChangePicture" class="changepic" id="changepic">Change picture</a>
	</div>
	<div class="user_info">
<div class="username"><?php echo $this->data["User"]["username"]; ?></div>
<?
echo $this->Form->create("User");
echo $this->Form->input('name', array('label' => 'Full name'));
echo $this->Form->input('email', array('label' => 'Email'));
echo $this->Form->input('new_password', array('label' => 'Password', 'type'=>'password'));
echo $this->Form->input('new_password2', array('label' => 'Repeat', 'type'=>'password'));
echo $this->Form->submit('Save changes', array('class'=>'submit'));
echo $this->Form->end();
?>

	</div>
</div>
<hr class="spacer" />
