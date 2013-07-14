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
			<button type="text" value="search">
				<i class="icon search"></i>
				<span class="text visuallyhidden">search</span>
			</button>
		</form>
	</div>
	<div class="col3 profile-wrapper">
		<div class="user profile">
			<div class="center-cell-wrapper">
				<div class="details center-cell">
					<span class="name">Mr Splashy Pants</span>
					<span class="email">splashy@passbolt.com</span>
				</div>
			</div>
		  <div class="picture left-cell">
				<img src="img/user.png" /> 
			</div>
		  <div class="more right-cell">
				<a href="#"><span>more</span></a>
			</div>
		</div>
	</div>
