<h2>Change your picture</h2>
<div class="facebox-container facebox-changepic">
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
<br/>
<em>Select a file on your computer using the browse button, and click on submit</em>
<?php
  	echo $this->Form->create('User',array('type' => 'file'));
	echo $this->Form->file('avatar'); 
	echo $this->Form->submit('Submit', array('class'=>'submit'));
	echo $this->Form->end();
?>
</div>