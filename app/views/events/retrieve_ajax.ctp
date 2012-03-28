<?php echo $this->element('activity/list', array("events"=>$events)); ?>
<?php if($count) echo $this->element('pagination', array('offset'=>$offset, 'count'=>$count, 'nb_results'=>$nb_results)); ?>
