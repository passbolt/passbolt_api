<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Passbolt - The simple password management system</title>
	<!--
           ____                  __          ____
          / __ \____  _____ ____/ /_  ____  / / /_
         / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
        / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
       /_/    \__,_/____/____/_.___/\____/_/\__/

       The password management solution
       (c) 2013 passbolt.com

	-->
	<base href="http://localhost/passbolt/">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width" name="viewport">
	<link href="css/reset.css" rel="stylesheet" type="text/css">
	<link href="css/grid.css" rel="stylesheet" type="text/css">
	<link href="css/font.css" rel="stylesheet" type="text/css">
	<link href="css/colors.css" rel="stylesheet" type="text/css">
	<link href="css/icons.css" rel="stylesheet" type="text/css">
	<link href="css/form.css" rel="stylesheet" type="text/css">
	<link href="css/buttons.css" rel="stylesheet" type="text/css">
	<link href="css/navigation.css" rel="stylesheet" type="text/css">
	<link href="css/popup.css" rel="stylesheet" type="text/css">
	<link href="css/tree.css" rel="stylesheet" type="text/css">
	<link href="css/breadcrumb.css" rel="stylesheet" type="text/css">
	<link href="css/table.css" rel="stylesheet" type="text/css">
	<link href="css/footer.css" rel="stylesheet" type="text/css">
	<link href="css/debug.css" rel="stylesheet" type="text/css">
	<link href="css/notificator.css" rel="stylesheet" type="text/css">
	<link href="css/header.css" rel="stylesheet" type="text/css">
	<link href="css/search.css" rel="stylesheet" type="text/css">
	<link href="css/sidebar.css" rel="stylesheet" type="text/css">
	<link href="css/workspace.password.css" rel="stylesheet" type="text/css">
	<link href="css/menu.dropdown.css" rel="stylesheet" type="text/css">
	<link href="css/scrollbar.css" rel="stylesheet" type="text/css"/>
	<script src="js/lib/compat/modernizr-2.6.2.min.js"></script>
</head>
<body>
<div id="container">
		<div class="mad_event_event_bus"></div>
		<div class="container_16 passbolt_controller_app_controller mad_view_view js_component js_state_ready" id="js_app_controller">
			<header>
				<div class="header">
					<nav>
						<div class="top navigation">
							<ul class="passbolt_controller_component_app_menu_controller mad_view_component_tree menu js_state_ready" id="js_menu">
								<li id="b7203df5-9fe6-3efd-3ba1-7a6d6eed5d91"><a href="#" class="home"><span class="label">home</span></a></li>
								<li id="c5951f7c-a119-172e-93aa-111ff7893261"><a href="#"><span class="label">passwords</span></a></li>
								<li id="90565458-c8ac-02d8-ec6d-70813a70301e"><a href="#"><span class="label">people</span></a></li>
								<li id="4473ba05-89ff-6f1b-5905-b581616b8f55"><a href="#"><span class="label">help</span></a></li>
								<li id="logout"><a href="logout"><span class="label">logout</span></a></li>
							</ul>
						</div>
					</nav>
					<div class="second" id="userbar">
						<div class="logo external">
							<h1><a href="#"><img src="img/logo/acme.png" alt="acme"/></a></h1>
						</div>
						<div class="notificator mad_core_singleton passbolt_view_component_notification js_component" id="js_notificator">this is a notification</div>
						<div class="search passbolt_controller_component_app_filter_controller passbolt_view_component_app_filter js_component js_state_ready" id="js_filter">
							<form class="mad_form_form_controller mad_view_view js_component" id="js_filter_form">
								<ul class="tags mad_form_element_list_controller mad_controller_component_tree_controller mad_view_component_tree tree mad_view_form_form_element_view js_component js_state_ready" id="js_filter_tags"></ul>
								<fieldset>
									<legend>Please enter a search keyword</legend>
									<div class="input text required">
										<label for="js_filter_keywords">Search</label>
										<input class="required mad_form_element_textbox_controller mad_view_form_element_textbox_view js_component js_state_ready" id="js_filter_keywords" maxlength="50" type="text"/>
									</div>
								</fieldset>
								<!--span class="control reset" id="js_filter_reset">x</span -->
								<div class="submit">
									<input type="submit"/>
								</div>
							</form>
						</div>
					</div>
				</div>
			</header>
			<div class="mad_controller_component_tab_controller mad_view_component_tab js_component js_state_ready" id="js_workspaces_container">
				<!-- <ul id="js_workspaces_container_selector" data-view-id='6'></ul> -->
				<div class="passbolt_controller_password_workspace_controller mad_view_view js_component js_state_ready js_workspace grid_16 alpha omega" id="js_passbolt_passwordWorkspace_controller" style="display: block;">
					<div class="action">
						<div class="workspace_label sidebar left">password</div>
						<div class="workspace_actions_container content full passbolt_controller_component_passwords_actions_menu_controller passbolt_view_component_passwords_actions_menu js_component js_state_ready" id="js_passbolt_password_actions_menu">
							<button class="mad_controller_component_button_controller mad_view_view js_component js_state_ready" id="js_request_resource_creation_button"><span class="text">create</span></button>
							<button class="mad_controller_component_button_controller mad_view_view js_component js_state_disabled disabled" disabled="disabled" id="js_request_resource_edition_button"><span class="text">edit</span></button>
							<button class="js_request_resource_deletion_button"><span class="text">delete</span></button>
							<button class="mad_controller_component_button_controller mad_view_view js_component js_state_disabled disabled" disabled="disabled" id="js_request_resource_sharing_button"><span class="text">share</span></button>
							<button class="mad_controller_component_button_controller mad_view_view js_component js_state_disabled disabled" disabled="disabled" id="js_request_resource_more_button"><span class="text">more</span></button>
						</div>
					</div>
					<!--div class="js_workspace_view_actions_container workspace_view_actions_container"></div>
					<div class="clear"></div-->
					<div class="js_workspace_sidebar_first sidebar left filterpanel scrollbar">
						<ul class="filters">
							<li><a href="#">All items</a></li>
							<li><a href="#">My Favorites</a></li>
							<li><a href="#">Shared with me</a></li>
							<li><a href="#">Expiring soon</a></li>
							<li><a href="#">Changed Recently</a></li>
						</ul>
						<ul class="passbolt_controller_component_category_chooser_controller mad_view_component_tree_dynamic_tree tree js_state_ready" id="js_passbolt_password_category_chooser">
							<li class="root node opened" id="50d77ffa-094c-4d4c-9dd7-1b63d7a10fce">
								<div>
									<a class="control close" href="#"><span>open</span></a>
									<a class="label" href="#">projects</a>
									<a class="control more" href="#"><span>more</span></a>
									<ul>
										<li class="root node opened" id="50d77ffc-08ac-42a8-b185-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">others</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffd-cf28-460e-b35e-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffc-0414-49dd-9959-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffc-08ac-42a8-b185-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">others</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffd-cf28-460e-b35e-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffc-0414-49dd-9959-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffc-08ac-42a8-b185-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">others</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffd-cf28-460e-b35e-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffc-0414-49dd-9959-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffc-08ac-42a8-b185-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">others</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffd-cf28-460e-b35e-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffc-0414-49dd-9959-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffc-08ac-42a8-b185-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">others</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffd-cf28-460e-b35e-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffc-0414-49dd-9959-1b63d7a10fce">
														<div>
															<a class="label" href="#">o-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffb-b9a0-415d-ba6a-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">drupal</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffc-8608-422a-8456-1b63d7a10fce">
														<div>
															<a class="label" href="#">d-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffb-8008-42d2-8811-1b63d7a10fce">
														<div>
															<a class="label" href="#">d-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
										<li class="root node opened" id="50d77ffa-5144-4b95-badd-1b63d7a10fce">
											<div>
												<a class="control close" href="#"><span>open</span></a>
												<a class="label" href="#">cakephp</a>
												<a class="control more" href="#"><span>more</span></a>
												<ul>
													<li class="leaf node" id="50d77ffb-d488-4217-9e2f-1b63d7a10fce">
														<div>
															<a class="label" href="#">cp-project3</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffa-c25c-4d92-aa35-1b63d7a10fce">
														<div>
															<a class="label" href="#">cp-project2</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
													<li class="leaf node" id="50d77ffa-4030-49e1-990d-1b63d7a10fce">
														<div>
															<a class="label" href="#">cp-project1</a>
															<a class="control more" href="#"><span>more</span></a>
														</div>
													</li>
												</ul>
											</div>
										</li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
					<div class="js_workspace_main content middle">
						<table class="passbolt_controller_component_password_browser_controller mad_view_component_grid mad_grid js_state_ready" id="js_passbolt_password_browser">
							<thead>
								<tr>
									<th class="js_grid_column js_grid_column_multipleSelect"></th>
									<th class="js_grid_column js_grid_column_name">Name</th>
									<th class="js_grid_column js_grid_column_username">Username</th>
									<th class="js_grid_column js_grid_column_uri">Uri</th>
									<th class="js_grid_column js_grid_column_modified">Modified</th>
									<th class="js_grid_column js_grid_column_copyLogin"></th>
									<th class="js_grid_column js_grid_column_copySecret"></th>
								</tr>
							</thead>
							<tbody>
								<tr id="50d77ffa-7278-41fc-a4bb-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffa-7278-41fc-a4bb-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffa-7278-41fc-a4bb-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>cpp1-pwd1 <span class="password_browser_category_label">cp-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://cake.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="a73e2cc8-8c93-d0e6-db6f-c7b5a95120d6" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="fa4f1046-e07d-c960-604c-419190949039" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffa-9b04-42e9-9974-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffa-9b04-42e9-9974-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffa-9b04-42e9-9974-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>cpp1-pwd2 <span class="password_browser_category_label">cp-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://cake.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="e7605a60-d338-a20b-69c4-5c755f017f8e" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="49ed95b4-875e-ad25-7817-c0263cfaf3da" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffb-40fc-472b-9fc6-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffb-40fc-472b-9fc6-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffb-40fc-472b-9fc6-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>cpp2-pwd1 <span class="password_browser_category_label">cp-project2</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://cake.project2.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="3e7255aa-96b3-8094-a9cf-c3afa0c0f755" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="4bf6786b-c980-f3b2-f71b-84add61879a3" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffb-afb4-4a73-85fd-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffb-afb4-4a73-85fd-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffb-afb4-4a73-85fd-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>cpp2-pwd2 <span class="password_browser_category_label">cp-project2</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://cake.project2.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="00155c46-d92b-48a4-c92b-a46bed5a356d" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="97cbe2e8-3f50-5abd-3336-80f29ffe5bfa" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffb-d254-49e4-ac86-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffb-d254-49e4-ac86-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffb-d254-49e4-ac86-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>dp1-pwd1 <span class="password_browser_category_label">d-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://drupal.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="580b30fa-2723-a8c4-5c46-8b4c8d8e8e21" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="3de965c9-6efb-d40a-2383-76006e0e645f" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffb-d290-49e4-ac86-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffb-d290-49e4-ac86-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffb-d290-49e4-ac86-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>dp2-pwd1 <span class="password_browser_category_label">d-project2</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://drupal.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="698c7882-9adf-411d-5acc-1b0389d9f1c2" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="40303a88-7a85-b4a5-b3fe-fe621529989f" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffd-3294-4db8-89f6-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffd-3294-4db8-89f6-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffd-3294-4db8-89f6-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>op1-pwd2 <span class="password_browser_category_label">o-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://other.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="4f765535-fc1d-de16-856e-0a4faec164b3" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="0b1d86ee-6fbf-b915-39e5-179fb15450f9" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffd-5624-492c-842e-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffd-5624-492c-842e-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffd-5624-492c-842e-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>shared resource <span class="password_browser_category_label">o-project2</span>
									<span class="password_browser_category_label">o-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://shared.resource.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="89368793-d022-21ef-64c7-1e31ee22863e" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="d8117df5-4fdb-7709-1674-87c41dfebf92" style="display: none;"></button></span></td>
								</tr>
								<tr id="50d77ffd-5624-492c-842e-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect"></td>
									<td class="js_grid_column_name"><span>shared resource <span class="password_browser_category_label">o-project2</span>
									<span class="password_browser_category_label">o-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://shared.resource.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"></td>
									<td class="js_grid_column_copySecret"></td>
								</tr>
								<tr id="50d77ffd-d54c-4bd3-b947-1b63d7a10fce">
									<td class="js_grid_column_multipleSelect">
										<div class="mad_form_element_checkbox_controller mad_view_form_element_checkbox_view js_checkbox_multiple_select js_state_ready" id="multiple_select_checkbox_50d77ffd-d54c-4bd3-b947-1b63d7a10fce">
											<span><input name="undefined" type="checkbox" value="50d77ffd-d54c-4bd3-b947-1b63d7a10fce"></span>
										</div>
									</td>
									<td class="js_grid_column_name"><span>op1-pwd1 <span class="password_browser_category_label">o-project1</span></span></td>
									<td class="js_grid_column_username"><span>admin</span></td>
									<td class="js_grid_column_uri"><span>http://other.project1.net/</span></td>
									<td class="js_grid_column_modified"><span>2 months ago</span></td>
									<td class="js_grid_column_copyLogin"><span><button class="passbolt_controller_component_copy_login_button_controller mad_view_view with_icon copy_login js_state_hidden" id="fc177327-b4c5-c1c3-e6a0-980c45ca9da6" style="display: none;"></button></span></td>
									<td class="js_grid_column_copySecret"><span><button class="passbolt_controller_component_copy_secret_button_controller mad_view_view with_icon copy_secret js_state_hidden" id="b8c0ecb5-ec09-9a48-471d-7cf37365dad0" style="display: none;"></button></span></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="js_workspace_sidebar_second sidebar right passbolt_controller_component_resource_details_controller passbolt_view_component_resource_details js_component" id="js_passbolt_password_sidebar_second"></div>
				</div>
				<div class="passbolt_controller_people_workspace_controller mad_view_view js_component js_state_hidden" id="js_passbolt_peopleWorkspace_controller" style="display: none;"></div>
				<div class="js_workspace_sidebar_second sidebar right passbolt_controller_component_resource_details_controller passbolt_view_component_resource_details js_component js_state_ready" id="js_passbolt_password_sidebar_second" style="display: block;"><h1>Linked In</h1>
				<div>
					<span class="">Modified</span> : 
					<span class="">a day ago</span>
				</div>
				<div>
					<span class="">Expire</span> : 
					<span class="">in an hour</span>
				</div>
				<div>
					<span class="">Strength</span> : 
					<span class=""></span>
				</div>
				<div>
					<button id="js_details_one_click_login_button">One click login</button>
					<button id="js_details_share_button">Share</button>
				</div>
				<h2>Description</h2>
				<p>this is a description test</p>
				<h2>Copy to clipboard</h2>
				<p>
					<button id="js_details_copy_login_button">login</button>
					<button id="js_details_copy_secret_button">password</button>
				</p>
				<h2>Tags</h2>
				<p>
					<span class="">Social</span>
					<span class="">biloute</span>
				</p>
				<h2>Comments</h2>
				<p style="display:none">comment</p>
				</div>
			</div>
		</div>
		<footer>
			<div class="footer">
				<span class="version">v.2.13.3</span> | <span class="copyright">2013 © Passbolt.com</span>
			</div>
		</footer>
<script type="text/javascript" src="js/lib/jquery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/lib/jquery/scrollbar.js"></script>
<script type="text/javascript" src="js/cssdemo/demo.js"></script>
		<!--script src="/passbolt/js/lib/moment/moment.min.js" type="text/javascript"></script-->
		<!--script src="/passbolt/js/steal/steal.js?app/passbolt.js" type="text/javascript"></script-->
	</div>
</body>
</html>
