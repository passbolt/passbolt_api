				<script>
				$(function() {
					$( "#settings" ).tabs();
					$("#settings").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
					$("#settings").removeClass('ui-corner-top').addClass('ui-corner-left');
				});
				</script>
				<style type="text/css">
	
					/* Vertical Tabs
					----------------------------------*/
					.ui-tabs-vertical { width: 100%; }
					.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 15%; margin-top:11px;  }
					.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
					.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
					.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-selected { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
					.ui-tabs-vertical .ui-tabs-panel { padding:0; }
				</style>
				<div class="window-header">
					<h2>Passbolt Settings</h2>
				</div>
				<div class="window-container events">
				  <div id="settings">
					  <ul class="settings-menu">
					  	<li><a href="#security">Security</a></li>
					  	<li><a href="#emails">Emails</a></li>
					  </ul>
					  <div id="security">
						<?php
						echo $this->Form->create();
						?>
						<fieldset>
							<legend>Master Key Settings</legend>
							<p>To change the master key, enter the current master key as well as the new one in the fields below</p>
						<?php
							echo $this->Form->input('current_master_key', array('label' => 'Current Master Key', 'type'=>'password', "after"=>"<em>Give the current Master Key</em>"));
							echo $this->Form->input('master_key', array('label' => 'Master Key', 'type'=>'password', "after"=>"<em>Type the new Master Key</em>"));
							echo $this->Form->input('master_key', array('label' => 'Master Key', 'type'=>'password', "after"=>"<em>Retype the new Master Key</em>"));
							echo $this->Form->input('master_key_lifetime', array('label' => 'Master Key Lifetime', "after"=>"<em>Validity time for the Master Key after it has been entered. Value is in seconds.</em>"));
							echo $this->Form->input('master_key_limit', array('type'=>'select', 'label'=>"Attempts limit", "options"=>array('0'=>'unlimited', '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5), "default"=>3, "after"=>"<em>Number of attempts before the application pauses.</em>" ));
							echo $this->Form->input('master_key_pause_time', array('label' => 'Pause Time', "after"=>"<em>Time for which the application should block before the user can retry enter his Master Key. Value is in minutes.</em>"));
						?>
						</fieldset>
						<?php
						echo $this->Form->submit('Save', array('class'=>'submit'));
						echo $this->Form->end();
						?>
						<hr class="spacer" />
					</div>
					<div id="emails">
						<?php
						echo $this->Form->create();
						?>
						<fieldset>
							<legend>Emails settings</legend>
							<p>This section enables you to configure your emails</p>
						<?php
							echo $this->Form->input('smtp_host', array('label' => 'SMTP Server', "after"=>"<em>The SMTP server to use to send emails</em>"));
							echo $this->Form->input('smtp_username', array('label' => 'Username', "after"=>"<em>Enter your username</em>"));
							echo $this->Form->input('smtp_password', array('label' => 'Password', 'type'=>'password', "after"=>"<em>Enter your corresponding password</em>"));
							echo $this->Form->input('smtp_port', array('label' => 'Port number', "after"=>"<em>The port to use on the server</em>"));
						?>
						</fieldset>
						<?php
						echo $this->Form->submit('Save', array('class'=>'submit'));
						echo $this->Form->end();
						?>
						<hr class="spacer" />
					</div>
				  </div>
				</div>