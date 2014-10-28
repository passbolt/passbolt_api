<?php
/**
 * Demo Permissions Dialog
 *
 * @copyright	copyright 2014 passbolt.com
 * @license		http://www.passbolt.com/license
 * @package		app.View.Elements.demo.dialog-share
 * @since		version 2.14.03
 */
	$dummy = array(
		0 => array ('name' => 'sysadmins','details' => 'group','group' => 1,'right' => 'update'),
		1 => array ('name' => 'remy','details' => 'remy@passbolt.com','group' => 0,'right' => 'read'),
		2 => array ('name' => 'kevin','details' => 'kevin@passbolt.com','group' => 0,'right' => 'admin'),
		3 => array ('name' => 'cedric','details' => 'cedric@passbolt.com','group' => 0,'right' => 'update'),
		4 => array ('name' => 'aurelie','details' => 'aurelie@passbolt.com','group' => 0,'right' => 'update'),
		5 => array ('name' => 'myriam','details' => 'myriam@passbolt.com','group' => 0,'right' => 'update'),
		6 => array ('name' => 'franck','details' => 'franck@passbolt.com','group' => 0,'right' => 'update')
	);
?>
<div class="dialog-wrapper">
	<div class="dialog">
		<div class="dialog-header">
			<h2>Edit Password</h2>
			<a href="#" class="dialog-close"><i class="icon close no-text"></i><span>close</span></a>
		</div>
		<div class="dialog-content">
			<div class="tabs">
				<ul class="tabs-nav">
					<li>
						<a href="pages/demo/demo#edit"><span>Edit</span></a>
					</li>
					<li>
						<a href="#" class="selected"><span>Share</span></a>
					</li>
					<li>
						<a href="pages/demo/dialog-organize"><span>Organize</span></a>
					</li>
				</ul>
				<ul class="tabs-content">
					<!-- share -->
					<li class="tab-content share-tab selected" id="b2164540-ea43-11e2-ac0e-0002a5d5c51c">
						<form class="perm-create-form clearfix">
						<div class="form-content permission-edit">
							<ul class="permissions scroll">
<?php foreach ($dummy as $i => $d): ?>
								<li class="row">
									<div class="avatar">
<?php if ($d['group']):?>
										<img src="img/group_default.png"/>
<?php else: ?>
										<img src="img/user.png"/>
<?php endif;?>
									</div>
									<div class="group">
										<span class="name"><?php echo $d['name'];?></span>
										<span class="details"><a href="#"><?php echo $d['details'];?></a></span>
									</div>
									<div class="select rights">
										<select id="js_permission_type" class="permission ">
											<option value="read" <?php if ($d['right']=='read'):?>selected="selected"<?php endif;?>>can use</option>
											<option value="update" <?php if ($d['right']=='udpate'):?>selected="selected"<?php endif;?>>can update</option>
											<option value="admin" <?php if ($d['right']=='admin'):?>selected="selected"<?php endif;?>>can share</option>
										</select>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>remove</span></a>
									</div>
								</li>
<?php endforeach; ?>
							</ul>
						</div>
						<div class="form-content permission-add">
							<input id="js_permission_aco" type="hidden" />
							<input id="js_permission_aro" type="hidden" />

							<div class="input text autocomplete">
								<label for="js_perm_create_form_aro_auto_cplt">Share with people or groups</label>
								<input type="text" id="js_perm_create_form_aro_auto_cplt" placeholder="start typing a person name" rows="2" class="small permision-add-input" />
							</div>
							<div class="select left perm-type">
								<select id="js_permission_type" class="permission ">
									<!--option value="create">create</option-->
									<option value="read">can use</option>
									<option value="update">can update</option>
									<option value="admin">can share</option>
								</select>
							</div>
							<div class="left actions">
								<input id="js_perm_create_form_add_btn" type="submit" class="button primary" value="add"/>
							</div>
							<div class="input checkbox send-email-notification">
								<input type="checkbox" name="select all" value="checkbox-send-email-notification" id="checkbox-send-email-notification">
								<label for="checkbox-send-email-notification">send a notification by email</label>
							</div>
						</div>
						<div class="submit-wrapper clearfix">
							<a href="#" class="cancel">cancel</a>
						</div>
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
