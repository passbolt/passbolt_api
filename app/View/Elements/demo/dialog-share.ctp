<?php
/**
 * Demo Permissions Dialog
 *
 * @copyright		 copyright 2013 passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.View.Elements.demo.dialog
 * @since				 version 2.13.09
 */
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
						<a href="#"><span>Organize</span></a>
					</li>
				</ul>
				<ul class="tabs-content">
					<!-- edit -->
					<li class="tab-content selected" id="b2164540-ea43-11e2-ac0e-0002a5d5c51c">
						<div class="form-content">
							<ul class="permissions">
								<!-- This is a group row-->
								<li class="row group clearfix">
									<div class="avatar">
										<div class="image-icon">
											<!-- Here we display an icon by default, otherwise the user avatar if present -->
											<i class="icon avatar group big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Sysadmins
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a default avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<!-- Here we display an icon by default, otherwise the user avatar if present -->
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-file">
											<!-- Here we display a custom avatar -->
											<img src="img/user.png" alt="your picture"  />
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
								<!-- This is a user row with a custom avatar -->
								<li class="row user clearfix">
									<div class="avatar">
										<div class="image-icon">
											<i class="icon avatar user big no-text"></i>
										</div>
									</div>
									<div class="details">
										<span class="name">
											Kevin Muller
										</span>
										<span class="rights">
											can edit
										</span>
									</div>
									<div class="actions">
										<a href="#" class="close"><i class="icon close no-text"></i><span>close</span></a>
									</div>
								</li>
								<!-- End row -->
							</ul>
							<!-- Actions (add permissions) -->
							<form class="perm-create-form clearfix">
								<input id="js_permission_aco" type="hidden" />
								<input id="js_permission_aro" type="hidden" />
								
								<div id="js_field_perm_aro_label" class="message">
								</div>
								
								<div class="input text left perm-aro">
									<label for="js_permission_aro_autocomplete">Share with a person or a group</label>
									<div class="autocomplete">
										<input class="loading" maxlength="50" type="text" id="js_permission_aro_autocomplete" placeholder="start typing a person or a group"/>
										<ul id="js_permission_aro_autocomplete_list" class="autocomplete-content" style="display:none;">
										</ul>
									</div>
								</div>
								
								<div class="select left perm-type">
									<select id="js_permission_type" class="permission error">
										<option value>select</option>
										<option value="create">create</option>
										<option value="read">read</option>
										<option value="update">update</option>
										<option value="admin">admin</option>
									</select>
								</div>
								
								<div class="left perm-actions">
									<a href="#" id="js_permission_add_button" class="button">
										<i class="plus icon big no-text"></i>
										<span>add permission</span>
									</a>
								</div>

					</div>	
								<div class="submit-wrapper clearfix">
									<input type="submit" class="button primary" value="save"/>
									<a href="#" class="cancel">cancel</a>
								</div>
							</form>
							<!-- End actions -->
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
