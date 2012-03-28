<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/transitional.dtd">

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Passbolt - The professional password management software</title>

    <!-- Framework CSS -->
    <link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">

    <!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <!-- Import fancy-type plugin for the sample page. -->
    <link rel="stylesheet" href="/css/blueprint/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/blueprint/liquid.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/main.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/facebox/facebox.css" type="text/css" media="screen, projection">
     <link rel="stylesheet" href="/css/jnotify/jquery.jnotify.min.css" type="text/css" media="screen, projection">
     <link rel="stylesheet" type="text/css" media="all" href="/js/jquery.modalbox-1.2.0/css/jquery.modalbox.css" />
      <link rel="stylesheet" type="text/css" media="all" href="/css/smoothness/jquery-ui-1.8.11.custom.css" />
     <link rel="stylesheet" type="text/css" media="all" href="/css/token-input/token-input.css" />
     <link rel="stylesheet" type="text/css" media="all" href="/css/token-input/token-input-mac.css" />
    
    <script type="text/javascript" src="/js/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script type="text/javascript" src="/js/jquery-validate/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/jquery.form.js"></script>
	<script type="text/javascript" src="/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/js/jquery.hotkeys.js"></script>
	<script type="text/javascript" src="/js/jquery.jnotify.min.js"></script>
	<script type="text/javascript" src="/js/token-input/jquery.tokeninput.js"></script>

	<script type="text/javascript" src="/js/jquery.modalbox-1.2.0/js/jquery.modalbox-1.2.0-min.js"></script>
	

	<script type="text/javascript" src="/js/jstree/jquery.jstree-rc2.js"></script>
	<script type="text/javascript" src="/js/locale/eng.js"></script>
	<script type="text/javascript" src="/js/jquery.passbolt.js"></script>
	<script type="text/javascript" src="/js/jquery.passbolt.password.js"></script>
	<script type="text/javascript" src="/js/facebox.js"></script>
	<script type="text/javascript" src="/js/ZeroClipboard/ZeroClipboard.js"></script>
	<script type="text/javascript" src="/js/users/index.js"></script>
	<script type="text/javascript" src="/js/aes.js"></script>
    
    <script type="text/javascript">
    	function concatObject(obj) {
    	  str='';
    	  for(prop in obj)
    	  {
    	    str+=("arr["+prop+"] = "+ obj[prop]+"<br/>\n");
    	  }
    	  return(str);
    	}
		
    	
    	$(function(){
    		var settings = {
    				"permUI":".sidebar .people",
    				"userAdmin":"<?php echo ($session->read('Auth.User.admin') ? '1' : '0') ?>"
    		};
    		var pk = $.passbolt._initialize(settings);
    		pk._databaseInitialize();
    	});
	</script>
	
	<?php if(isset($master_key_set) && $master_key_set == 0): ?>
	 <script type="text/javascript">
	 	$(function(){
	 		$.jnotify("<div class=\"explanations\">Your are currentlt using the default settings of PassBolt. By default, PassBolt doesn't encrypt the passwords in the database. " +
	 		 		"This is absolutely not secured and it is strongly advised to set a master key that will be shared among your collaborators so PassBolt can encrypt the passwords.<br/>"+
	 		 		"<a href=\"#\">Know more about the master key</a></div>"+
	 		 		"<div class=\"form\">"+
	 		 		"<form method=\"post\" action=\"\">"+
	 		 		"<div class=\"input text\"><label>Master Key</label><input type=\"text\" name=\"\"><em>Enter above the master key that you will share with your collaborators</em></div>"+
	 		 		"<div class=\"input text\"><label>Valid for</label><select name=\"duration\">"+
	 		 		"<option value=\"0\">no limit</option>"+
	 		 		"<option value=\"15m\">15 minutes</option>"+
	 		 		"<option value=\"1h\">one hour</option>"+
	 		 		"<option value=\"3h\">three hours</option>"+
	 		 		"<option value=\"1d\">one day</option></select><em>For how long the master key will be remembered by the browser</em></div>"+
	 		 		"</form>"+
	 		 		"</div><hr class=\"spacer\" />"+
	 		 		"<a href=\"\">I want to keep the current settings, thanks</a><a href=\"\">Save new settings</a>", 
	 		 		"masterkey", 
	 		 		true
	 		 	);
		 });
	 </script>
	<?php endif; ?>
  </head>
  <body>
	<div class="container">
		<div id="header" class="span-24">
			<div class="header-container span-23">
				<div class="logo"><img src="/img/logo.png" /></div>
				<ul class="menu span-15">
					<li<?php if($section == 'passwords'): ?> class="active"<?php endif; ?>><a href="/categories">Passwords</a></li>
					<li<?php if($section == 'people'): ?> class="active"<?php endif; ?>><a href="/users">People</a></li>
					<li<?php if($section == 'activity'): ?> class="active"<?php endif; ?>><a href="/activity">Activity</a></li>
					<?php if ($session->read('Auth.User.admin')): ?>
					<li<?php if($section == 'settings'): ?> class="active"<?php endif; ?>><a href="/settings">Settings</a></li>
					<?php endif; ?>
					
				</ul>
				<div class="right_header">
					<ul class="account_context">
						<li class="first"><?php echo $session->read('Auth.User.name'); ?></li>
						<li><a href="/users/account">My Account</a></li>
						<li><a href="/users/logout">Sign Out</a></li>
					</ul>
					<hr class="spacer" />
				</div>
			</div>
		</div>
		<div class="body-container">
			<div id="body" class="span-23">
				<div class="window span-18">
					<?php echo $content_for_layout; ?>
				</div>
				
				<div class="sidebar">
					<?php
					switch($section){
						case 'passwords':
							echo $this->element('sidebar/passwords_info', array("section"=>$section));
							echo $this->element('sidebar/passwords_access', array());
							echo $this->element('sidebar/activity', array("section"=>$section));
							break;	
						case 'people':
							echo $this->element('sidebar/passwords_info', array("section"=>$section));
							echo $this->element('sidebar/groups', array("groups"=>$groups));
							echo $this->element('sidebar/activity', array("section"=>$section));
							break;
						case 'activity':
							echo $this->element('sidebar/activity_filter', array("section"=>$section));
							break;
						case 'settings':
							break;
					}
					?>
					<?php   ?>
					<hr class="spacer" />
				</div>
				<hr class="spacer" />
			</div>
		</div>

		<div id="footer">
			<div class="footer-container span-23">
			Copyright E-nova Technologies Pvt. Ltd.
			</div>
		</div>
	</div>
	<div class="debug">
	<?php pr($debug); ?>
	</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>