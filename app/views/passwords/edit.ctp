<h2>Edit a password</h2>
<div class="facebox-container">
<?php
echo $this->Form->create();
?>
<?
echo $this->Form->input('category_id', array('label' => 'Category', 'type'=>'hidden'));
echo $this->Form->input('title', array('label' => 'Title'));
echo $this->Form->input('username', array('label' => 'Username'));
echo $this->Form->input('password', array('label' => 'Password', 'after'=>'<a href="#" id="generate">Gen</a>', 'autocomplete'=>'off'));
?>
<div class="pw_score">
	<span class="label">Password strength:</span><span class="score-verdict"></span>
	<hr class="spacer" />
	<div class="progress">
		<div class="score"></div>
	</div>
</div>
<?php
echo $this->Form->input('passwordRepeat', array('label' => 'Repeat', 'value'=>$this->data['Password']['password'],'type'=>'password', 'after'=>'<a href="#" id="see">See</a>', 'autocomplete'=>'off'));
echo $this->Form->input('url', array('label' => 'Url/Ip'));
echo $this->Form->textarea('comment', array('label' => 'Comment'));
echo $this->Form->submit('Save', array('class'=>'submit'));
echo $this->Form->end();
?>
</div>