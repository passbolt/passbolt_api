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
						<a href="pages/demo/dialog-share"><span>Share</span></a>
					</li>
					<li>
						<a href="pages/demo/dialog-organize" class="selected"><span>Organize</span></a>
					</li>
				</ul>
				<ul class="tabs-content">
					<!-- edit -->
					<li class="tab-content organize-tab selected" >
						<form class="perm-create-form clearfix">
						<div class="form-content folder-edit">

	<div class="current-folder">
		<span>the resource is currently in this folder:<span>
		<span class="folder-name">category de test</span> 
	</div>

	<div class="tree categories scroll">
		<ul>
			<li class="open node root">
				<div class="row">
					<div class="main-cell-wrapper">
						<div class="main-cell">
							<a href="#" title="Home"><span>Home</span></a>
						</div>
					</div>
					<div class="left-cell node-ctrl">
						<a href="#open"><span>open/close</span></a>
					</div>
					<div class="right-cell more-ctrl">
						<a href="#more"><span>more</span></a>
					</div>
				</div>
				<ul>
					<li class="open node">
						<div class="row selected">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#"><span>Category de test</span></a>
								</div>
							</div>
							<div class="left-cell node-ctrl">
								<a href="#open"><span>open/close</span></a>
							</div>
							<div class="right-cell more-ctrl">
								<a href="#more"><span>more</span></a>
							</div>
						</div>
						<ul>
							<li class="close node">
								<div class="row">
									<div class="main-cell-wrapper">
										<div class="main-cell">
											<a href="#"><span>Leaf category</span></a>
										</div>
									</div>
									<div class="left-cell node-ctrl">
										<a href="#open"><span>open/close</span></a>
									</div>
									<div class="right-cell more-ctrl">
										<a href="#more"><span>more</span></a>
									</div>
								</div>
							</li>
						</ul>
					</li>
				</ul>
<?php for($i= 0 ; $i < 4; $i++): ?>
				<ul>
					<li class="open node">
						<div class="row">
							<div class="main-cell-wrapper">
								<div class="main-cell">
									<a href="#"><span>Category</span></a>
								</div>
							</div>
							<div class="left-cell node-ctrl">
								<a href="#open"><span>open/close</span></a>
							</div>
							<div class="right-cell more-ctrl">
								<a href="#more"><span>more</span></a>
							</div>
						</div>
						<ul>
							<li class="open node">
								<div class="row">
									<div class="main-cell-wrapper">
										<div class="main-cell">
											<a href="#"><span>Leaf category</span></a>
										</div>
									</div>
									<div class="left-cell node-ctrl">
										<a href="#open"><span>open/close</span></a>
									</div>
									<div class="right-cell more-ctrl">
										<a href="#more"><span>more</span></a>
									</div>
								</div>
							</li>
							<ul>
								<li class="open node">
									<div class="row">
										<div class="main-cell-wrapper">
											<div class="main-cell">
												<a href="#"><span>Category 2</span></a>
											</div>
										</div>
										<div class="left-cell node-ctrl">
											<a href="#open"><span>open/close</span></a>
										</div>
										<div class="right-cell more-ctrl">
											<a href="#more"><span>more</span></a>
										</div>
									</div>
									<ul>
										<li class="open node">
											<div class="row">
												<div class="main-cell-wrapper">
													<div class="main-cell">
														<a href="#"><span>Leaf category 3</span></a>
													</div>
												</div>
												<div class="left-cell node-ctrl">
													<a href="#open"><span>open/close</span></a>
												</div>
												<div class="right-cell more-ctrl">
													<a href="#more"><span>more</span></a>
												</div>
											</div>
										</li>
									</ul>
								</li>
							</ul>
						</ul>
					</li>
				</ul>
<?php endfor;?>
			</li>
		</ul>
	</div>

						</div>
						<div class="form-content folder-add clearfix">
							<div class="input text required">
								<label for="ResourceTitle">Add a new folder</label>
								<input name="data[Category][name]" class="required" type="text" id="CategoryName" placeholder="enter a folder name">
								<!--div class="message error">the folder name is required</div-->					
							</div>
							<a href="#" class="button add">
								<i class="icon add big no-text"></i>
								<span>add</span>
							</a>
						</div>
						<div class="submit-wrapper clearfix">
							<input type="submit" class="button primary" value="save"/>
							<a href="#" class="cancel">cancel</a>
						</div>
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
