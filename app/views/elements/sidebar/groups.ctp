					<div class="box groups">
						<h2>Groups for this user </h2>
						<div class="box-content">
						
							<div class="unavailable">Select a user on the left to see the corresponding groups.</div>
							<div class="available">
								<div class="groups">
								<?php if($session->read('Auth.User.admin')): ?>
								<a href="#" id="managegroups">Manage groups</a>
								<?php endif; ?>
								<?php if($session->read('Auth.User.admin')): ?>
								<div id="groups-management" class="jstree">
									<?php
										echo $this->Tree->generate($groups, array('element'=>'node_group', 'elementIdField'=>'id', 'elementRelField'=>'rel','model'=>'Group', 'depth'=>'30')); 
									?>
								</div>
								<?php else: ?>
								<div id="groups-management">
								
								</div>
								<?php endif; ?>
								</div>
								<div class="admin">
									<span class="username">User</span> is an admin
								</div>
							</div>
						</div>
					</div> 