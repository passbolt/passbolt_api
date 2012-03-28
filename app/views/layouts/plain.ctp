<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 

  <title>Passbolt - login</title>
  <link rel="stylesheet" href="/css/login.css" type="text/css" media="screen, projection">
  <script type="text/javascript" src="/js/jquery-1.5.2.min.js"></script>
  <script type="text/javascript" src="/js/login.js"></script>
</head>
<body>
<div id="wrap">
	<div class="login-container">
	<!-- TOP CONTAINER -->
	  <div class="form-top">
		<h1>
		Passbolt makes password management easy!
		<span>The perfect password management solution for businesses and small companies</span>
		</h1>
	  </div>
	<!-- TOP CONTAINER END -->
	
	<!-- BODY -->
	      <div class="login_dialog">
	      <?php 
			echo $content_for_layout;
	  ?>
		<!-- LOGIN CENTERED END -->	
		</div>
	<!-- BODY END -->
	
	</div>
</div>
<!-- FOOTER -->
	<div class="login-footer">
	  <p>Passbolt is a software of E-nova Technologies Pvt.Ltd.</p>
	</div>
<!-- FOOTER END -->
<?php // echo $this->element('sql_dump'); ?>
</body>
</html>
