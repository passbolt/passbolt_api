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
			<h2>Logs</h2>
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
									<a href="#"><span>Share</span></a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#" class="selected"><span>Logs</span></a>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<ul class="tabs-content">
					<!-- edit -->
					<li class="tab-content selected" id="b2164540-ea43-11e2-ac0e-0002a5d5c51c">
						<div class="form-content">
							<div class="logs">
								<div class="heading clearfix">
									<span class="user">User</span>
									<span class="date">Date</span>
								</div>
								<ul>
									<li class="row log">
										<div class="clearfix">
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Created a new password <a href="#">password1</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Commented on password <a href="#">password1</a> : <a href="#">Hey you, just shut the ....</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>added password <a href="#">bank account</a> to category <a href="#">bank passwords</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Created a new password <a href="#">password1</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Commented on password <a href="#">password1</a> : <a href="#">Hey you, just shut the ....</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>added password <a href="#">bank account</a> to category <a href="#">bank passwords</a></p>
									</li>
									<li class="row log">
										<div class="clearfix">
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Created a new password <a href="#">password1</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>Commented on password <a href="#">password1</a> : <a href="#">Hey you, just shut the ....</a></p>
									</li>
									<li class="row log">
										<div>
											<span class="user">Kevin Muller</span>
											<span class="date">12/12/2013 00:30:00</span>
										</div>
										<p>added password <a href="#">bank account</a> to category <a href="#">bank passwords</a></p>
									</li>
								</ul>
								<!-- Actions (filter logs) -->
								<form class="logs-filter-form clearfix">
									<div class="select log-action-type">
										<span class="select-box clearfix">
											<select id="js_action_type" class="action">
												<option value>select action</option>
												<optgroup label="Comments">
													<option value="create">created comment comment</option>
													<option value="read">deleted comment</option>
													<option value="update">replied to comment</option>
												<optgroup label="Passwords">
													<option value="create">created resource</option>
													<option value="read">updated resource</option>
													<option value="update">deleted resource</option>
													<option value="update">moved resource</option>
											</select>
										</span>​
									</div>

									<div class="input text log-user-group">
										<div class="autocomplete">
											<input class="loading" maxlength="50" type="text" id="js_log_user_group_autocomplete" placeholder="person or group"/>
											<ul id="js_log_user_group_autocomplete_list" class="autocomplete-content" style="display:none;">
											</ul>
										</div>
									</div>

									<div class="input-datetime-wrapper log-start-date">
										<div class="input text datetime short">
											<input name="data[Log][date_start]" class="required" type="text" id="FilterDateStart" data-format="YYYY-MM-DD HH:mm" data-template="D MMM YYYY HH:mm" placeholder="dd/mm/yyyy hh:mm">
											<div class="actions inline dropdown">
												<a href="#" class="button">
													<i class="clock icon big no-text"></i>
													<span>pick a date and time</span>
												</a>
											</div>
										</div>
									</div>

									<div class="input-datetime-wrapper log-end-date">
										<div class="input text datetime short">
											<input name="data[Log][date_end]" class="required" type="text" id="FilterDateEnd" data-format="YYYY-MM-DD HH:mm" data-template="D MMM YYYY HH:mm" placeholder="dd/mm/yyyy hh:mm">
											<div class="actions inline dropdown">
												<a href="#" class="button">
													<i class="clock icon big no-text"></i>
													<span>pick a date and time</span>
												</a>
											</div>
										</div>
									</div>

									<div class="actions">
										<div class="left log-filter">
											<a href="#" id="js_log_filter" class="button">
												<span>Filter</span>
											</a>
										</div>
										<div class="left log-filter-cancel">
											<a href="#">or cancel</a>
										</div>
									</div>
								</form>
								<!-- End actions -->
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
