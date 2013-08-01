<?php
/**
 * Navigation Template Demo
 *
 * @copyright     copyright 2013 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.demo.tree
 * @since         version 2.13.02
 */
?>
	<!-- navigation tree -->
	<div class="navigation first shortcuts ">
		<ul>
			<li><div class="row selected"><a href="#" >All items</a></div></li>
			<li><div class="row"><a href="#" >Favorite</a></div></li>
			<li><div class="row"><a href="#" >Most used</a></div></li>
			<li><div class="row"><a href="#" >Recently modified</a></div></li>
			<li><div class="row"><a href="#" >Expiring soon</a></div></li>
			<li><div class="row"><a href="#" >Shared with me</a></div></li>
			<li><div class="row"><a href="#" >Items I own</a></div></li>
		</ul>
	</div>
	<div class="navigation last tree categories">
		<ul>
			<li class="open node root">
				<div class="row">
					<div class="main-cell-wrapper">
						<div class="main-cell">
							<a href="#"><span>Category</span></a>
						</div>
					</div>
					<div class="left-cell node-ctrl">
						<a href="#"><span>open/close</span></a>
					</div>
					<div class="right-cell more-ctrl">
						<a href="#"><span>more</span></a>
					</div>
				</div>
				<ul>
					<li class="close node">
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#"><span>Category</span></a>
								</div>
							</div>
							<div class="left-cell node-ctrl">
								<a href="#"><span>open/close</span></a>
							</div>
							<div class="right-cell more-ctrl">
								<a href="#"><span>more</span></a>
							</div>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</div>
