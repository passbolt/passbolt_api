<?php
/**
 * Demo Header Bellow Nav
 *
 * @copyright     copyright 2013 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.demo.header
 * @since         version 2.13.02
 */
?>
	<!-- logo and search -->
	<div class="col1">
		<div class="logo">
			<img src="img/logo/acme.png" alt="passbolt"/>
		</div>
	</div>
	<div class="col2 search-wrapper">
		<form class="search">
			<div class="input search required">
				<label for="search">Search</label>
				<input id="search" class="required js_search" maxlength="50" type="search" placeholder="search">
			</div>
			<button value="search">
				<i class="icon search"></i>
				<span class="text visuallyhidden">search</span>
			</button>
		</form>
	</div>
	<div class="col3 profile-wrapper">
		<div class="user profile dropdown">
			<div class="center-cell-wrapper">
				<div class="details center-cell">
					<span class="name">Mr Splashy Pants</span>
					<span class="email">splashy@passbolt.com</span>
				</div>
			</div>
		  <div class="picture left-cell">
				<img src="img/user.png" alt="your picture"/> 
			</div>
		  <div class="more right-cell">
				<a href="#" data-dropdown-content-id="cc1d4fae-7dec-11d0-a765-00a0c91e6bf6"><span>more</span></a>
			</div>
			<div class="dropdown-content right" id="cc1d4fae-7dec-11d0-a765-00a0c91e6bf6">
				<ul>
					<li><a href="#">my profile</a></li>
					<li><a href="#">manage or generate new keys</a></li>
					<li><a href="#" class="separator-after">manage people</a></li>
					<li><a href="logout">logout</a></li>
				</ul>
			</div>
		</div>
	</div>
