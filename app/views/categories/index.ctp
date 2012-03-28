				<div class="window-header">
					<div id="database_select">
						<span>database::</span>
						<select name="database">
							<?php foreach($databases as $db): ?>
							<option value="<?= $db['Category']['id'] ?>"<?= ($session->read('database_id') == $db['Category']['id'] ? ' selected="selected"' : '') ?>><?= $db['Category']['name'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php if($session->read('Auth.User.admin')): ?>
						
						<a href="/categories/createDatabase" id="newdatabaselink">create database</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="window-container">
					<div class="categories span-5">
						<div class="categories-border">
							<div class="column_head">Categories</div>
							<div id="categories" class="jstree">
								<?php
									echo $this->Tree->generate($categories, array('element'=>'node_category', 'elementIdField'=>'id', 'elementRelField'=>'rel', 'elementClassField'=>'class', 'model'=>'Category', 'depth'=>'30')); 
							?>
							</div>
						</div>
						
					</div>
					<div class="passwords">
						<table>
							<thead>
								<tr>
									<td class="title">title</td>
									<td class="username">username</td>
									<td class="password">password</td>
									<td class="url">IP/url</td>
									<td class="actions">&nbsp;</td>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						
						<a href="/passwords/add" id="addPasswordButton" class="button add" rel="facebox"><span>Add a password</span></a>
					</div>
				</div>