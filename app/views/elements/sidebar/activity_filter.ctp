				<div class="box activity-filters">
						<h2>Filters</h2>
						<div class="box-content">
							<?php echo $this->Form->create('Event'); ?>
							<?php if ($session->read('Auth.User.admin')): ?>
							<fieldset>
								<legend>Users</legend>
								<input type="text" name="data[User][id]" class="users text" value="users" />
							</fieldset>
							<?php endif; ?>
							<fieldset class="dates">
								<legend>Dates</legend>
								<div class="dates">
									<span class="from"><label>From</label><input type="text" name="data[Event][datefrom]" class="datefrom text" autocomplete="off" /></span>
									<span class="to"><label>To</label> <input type="text" name="data[Event][dateto]" class="dateto text" autocomplete="off" /></span>
								</div>
							</fieldset>
							<fieldset class="actions">
								<legend>Actions</legend>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="1" /><label>Access Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="2" /><label>Access Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="3" /><label>Rename Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="4" /><label>Remove Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="5" /><label>Delete Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="6" /><label>Move Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="7" /><label>Copy Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="8" /><label>Create Category</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="9" /><label>Create Database</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="10" /><label>Open Database</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="11" /><label>Create Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="12" /><label>Remove Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="13" /><label>Delete Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="14" /><label>Restore Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="15" /><label>Modify Password</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="16" /><label>Add Permission</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="17" /><label>Delete Permission</label></div>
								<div class="action"><input type="checkbox" class="checkbox" name="data[Event][action_id][]" value="18" /><label>Modify Permission</label></div>
							</fieldset>
							<input type="submit" class="submit" value="Filter" />
							<a href="#" class="button reset">Reset</a>
							<?php echo $this->Form->end(); ?>
						</div>
				</div>