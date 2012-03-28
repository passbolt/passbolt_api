<?php echo $form->create('Tag');?>

<?php echo $form->input(
'Group.id',
array('type'=>'hidden', 'value' => $group_id)); ?>
 <?php echo $form->end('Add User'); ?>
