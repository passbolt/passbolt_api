<?php
	echo $this->Tree->generate($categories, array('element'=>'node_category', 'elementIdField'=>'id', 'elementRelField'=>'rel', 'elementClassField'=>'class', 'model'=>'Category', 'depth'=>'30')); 
?>