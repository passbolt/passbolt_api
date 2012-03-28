					<div class="box people">
						<h2>Access rights </h2>
						<div class="box-content">
							<div class="unavailable">Select a category on the left to see the corresponding access rights.</div>
							<div class="available">
								<?php if($session->read('Auth.User.admin')): ?>
								<a href="#" id="addperm">Add</a>
								<?php endif; ?>
								<div class="formperm">
									<form name="permissions_management_form" method="post" action="/permissions/save" id="permissions_management_form">
										<input type="hidden" name="perm_aco_id" id="perm_aco_id" value="-1" />
										<input type="hidden" name="perm_aco_ref_id" id="perm_aco_ref_id" value="-1" />
										<select name="perm_group_id" id="groups_select">
										</select>
										<select name="perm_user_id" id="users_select">
										</select>
										<select name="permissions" id="permissions_select">
											<option value="---">Deny all</option>
											<option value="r--">Read only</option>
											<option value="rw-">Read and modify</option>
											<option value="rwm">Read, modify and manage</option>
										</select>
										<input type="submit" id="saveperm" value="Save">
										<hr class="spacer" />
									</form>
								</div>
								<ul class="groups">
									<li>No groups</li>
								</ul>
							</div>
							<hr/>
						</div>
					</div>