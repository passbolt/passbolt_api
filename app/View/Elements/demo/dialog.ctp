<?php
/**
 * Demo Dialog
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
    <a href="#" class="icon close no-text"><span>close</span></a>
	</div>
	<div class="dialog-tabs">
		<ul class="tabs">
			<li><a href="#" class="selected" item-selected="b2164540-ea43-11e2-ac0e-0002a5d5c51b">edit</a></li>
			<li><a href="#">share</a></li>
			<li><a href="#">organize</a></li>
			<li><a href="#">logs</a></li>
		</ul>
		<div class="dialog-tabs-content">
			<!-- edit -->
			<div class="dialog-tab-content" class="selected" id="b2164540-ea43-11e2-ac0e-0002a5d5c51b">
				<div class="input text required">
					<label for="ResourceTitle">Title</label>
					<input name="data[Resource][title]" class="required" maxlength="50" type="text" id="ResourceTitle"/>
				</div>
				<div class="input text required">
					<label for="ResourceURL">URL</label>
					<input name="data[Resource][url]" class="required" maxlength="50" type="text" id="ResourceUrl"/>
				</div>
				<div class="input text required">
					<label for="ResourceLogin">Login</label>
					<input name="data[Resource][login]" class="required" maxlength="50" type="text" id="ResourceLogin"/>
				</div>
				<div class="input-password-wrapper">
					<div class="input password required">
						<label for="SecretData">Password</label>
						<input name="data[Secret][data]" class="required" maxlength="50" type="password" id="SecretData"/>
					</div>
					<div class="password-actions">
						<a href="#" class="button no-text">
							<i class="icon eye"></i>
							<span>view</span>
						</a>
						<a href="#" class="button no-text">
							<i class="cog icon"></i>
							<span>generate</span>
						</a>
					</div>
					<div class="password-complexity good">
						<span class="progress"><span class="progress-bar"></span></span>
						<span class="complexity-text">kind of</span>
					</div>
				</div>
			</div>
			<div class="submit-wrapper">
				<div class="submit">
					<input  type="submit" value="save"/>
				</div>
				<a href="#">cancel</a>
			</div>
		</div>
	</div>
</div>
</div>
