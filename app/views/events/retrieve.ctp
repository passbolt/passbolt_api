				<div class="window-header">
					<h2>Passbolt Activity</h2>
					<a href="/passwords/add" id="addPasswordTopButton" class="action">Add password</a>
				</div>
				<div class="window-container events">
				   <?php echo $this->element('activity/list', array("events"=>$events)); ?>
				   <?php if($count) echo $this->element('pagination', array('offset'=>$offset, 'count'=>$count, 'nb_results'=>$nb_results)); ?>
				</div>