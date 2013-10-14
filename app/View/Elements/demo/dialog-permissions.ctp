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
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#"><span>Edit</span></a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#" class="selected"><span>Share</span></a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#"><span>Organize</span></a>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<ul class="tabs-content">
					<!-- edit -->
					<li class="tab-content selected" id="b2164540-ea43-11e2-ac0e-0002a5d5c51c">
						<form>
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
								<div class="perm-actions clearfix">
									<div class="input text short">
										<label for="UserName">Share with a person or a group</label>
										<input class="loading" name="data[User][name]" maxlength="50" type="text" id="UserName" placeholder="start typing a person or a group"/>
										<div class="actions inline dropdown">
											<select name="data[Permission][type]" class="permission">
												<option value>choose</option>
												<option value="read">read</option>
												<option value="write">write</option>
												<option value="admin">admin</option>
											</select>

											<a href="#" class="button">
												<i class="plus icon big no-text"></i>
												<span>add permission</span>
											</a>
										</div>
										<!-- The line below is displayed only after the user modifies (add / delete) a permission -->
										<em>Changes are temporary only. Click on save to make them permanent.</em>
									</div>
								</div>
								<!-- End actions -->
							</div>
							<div class="submit-wrapper clearfix">
								<input type="submit" class="button" value="save"/>
								<a href="#" class="cancel button">cancel</a>
							</div>
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
