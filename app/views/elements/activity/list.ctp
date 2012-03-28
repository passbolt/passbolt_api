				<?php $i = 0; ?>
				<?php 
				if(sizeof($events) == 0){
				?>
					<div class="no_event">No events to display</div>
				<?php
				}
				else{
				?>
				<ul class="events">
					<?php
					$eventHelper = $event;
					foreach($events as $event){
					?>
					<li rel="<?php echo $event["Event"]["id"] ?>" class="<?php echo ($i%2 == 0 ? 'even' : 'odd')  ?>">
						<div class="image">
							<?php if(isset($event["User"]["avatar"]) && !empty($user["User"]["avatar"])): ?>
							  <img src="/img/users/small/<?php  echo $event["User"]["avatar"]; ?>" width="35" height="35">
							  <?php else: ?>
							  	 <img src="/img/users/avatar.png" width="45" height="45">
							  <?php endif; ?>
						</div>
						<div class="information">
							<div class="entry">
							<?php 
							  echo $eventHelper->toText($event);
							?>
							</div>
							<div class="date">
								<?php
								echo $date->relativeTime(strtotime($event["Event"]["date"]));
								?>
							</div>
						</div>
						<hr class="spacer" />
					</li>
				<?php
				 	$i++;
					}
				?>
				</ul>
				<?php
				}
				?>