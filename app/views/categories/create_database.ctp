<h2>Add a new database</h2>
<div id="window-createdb">
<?php
echo $this->Form->create("Category");
?>
<div class="createdatabase">
<?
echo $this->Form->input('name', array('label' => 'Database' , 'maxlength'=>'20'));
echo $this->Form->submit('Save', array('class'=>'submit'));
?>
</div>
</div>
