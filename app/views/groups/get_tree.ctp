<?php
	echo $this->Tree->generate($groups, array('element'=>'node_group', 'elementIdField'=>'id', 'elementRelField'=>'rel','model'=>'Group', 'depth'=>'30')); 
?>