
				<div class="window-header">
					<h2>Users and groups</h2>
					<?php if($session->read('Auth.User.admin')): ?>
					<a href="/users/add" id="addUserTopButton" class="action">Add user</a>
					<?php endif; ?>
				</div>
				<div class="window-container people">
					<ul class="users">
					<?php foreach($users as $user): ?>
						<li id="<?php echo $user['User']['id'] ?>"<?php if(!$user['User']['active']) echo "class=\"deactivated\""; ?>>
							  <div class="image">
							  <?php if(isset($user["User"]["avatar"]) && !empty($user["User"]["avatar"])): ?>
							  <img src="/img/users/small/<?php  echo $user["User"]["avatar"]; ?>" width="55" height="55">
							  <?php else: ?>
							  	 <img src="/img/users/avatar.png" width="55" height="55">
							  <?php endif; ?>
							  <?php if($user['User']["admin"]): ?><em>admin</em><?php endif; ?>
							  </div>
							<ul class="details">
								<li class="name"><?php  echo $user['User']['name'] ?></li>
								<li class="email"><?php  echo $user['User']['email'] ?></li>
								<li class="lastlogin">last login : <?php echo (!empty($user['User']['last_login_date']) ? $date->relativeTime(strtotime($user['User']['last_login_date'])) : 'never'); ?></li>
								<?php if($session->read('Auth.User.admin')): ?>
								<li class="edit"><a href="" class="edit">edit</a> | <a href="" class="email">email</a> | <a href="" class="deactivate"<?php if(!$user['User']['active']): ?> style="display:none;"<?php endif; ?>>deactivate</a><a href="" class="activate"<?php if($user['User']['active']): ?> style="display:none;"<?php endif; ?>>activate</a> | <a href="" class="remove">remove</a></li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				