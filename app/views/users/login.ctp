	  <!-- LOGIN CENTERED -->
	  <div class="logn-main">
		<div id="user_name_login" class="login_type">
	    <h2>Sign in with your<span>Passbolt ID</span></h2>
		<?php
		    if  ($session->check('Message.auth')) $session->flash('auth');
   			echo $form->create('User', array('action' => 'login'));
    		echo $form->input('username', array('before'=>'<span class="overlay_wrapper">', 'after'=>'</span>', 'label'=>array('class'=>'overlabel', 'text'=>'Username or email')));
    		echo $form->input('password', array('before'=>'<span class="overlay_wrapper">', 'after'=>'</span>', 'label'=>array('class'=>'overlabel')));
    		echo $form->end('Login');
		?>
	</div>
</div>
             