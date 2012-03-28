<div id="window-masterkey">
	<div class="facebox-container">
		<div class="masterkey">
			<?= $this->Form->create("Password"); ?>
			
			<?= $this->Form->input('masterkey', array('label' => 'Before continuing, you must enter the master key', 'type'=>'password')); ?>
			
			<?= $this->Form->submit('OK', array('class'=>'submit')); ?>
			
			<?= $this->Form->end(); ?>
			
			<hr class="spacer" />
			<a href="#" class="help">Know more about the master key</a>
			<div class="helpContent">
				<h2>What is this master key ?</h2>
				<p>The master key is a password / passphrase used to encrypt and decrypt all the passwords entered in PassBolt. You can compare it to a wireless key. It is an additional security measure to make sure that the passwords are accessible only by the allowed persons. In other terms, if you don't have the master key, you will not be able to access the passwords stored in passbolt.</p>
				<h2>Whom should I contact to get the master key ?</h2>
				<p>The master key is defined by the super administrator of this PassBolt installation. The master key should never be sent by email or any written document, so we advise you to meet the person in charge of it directly.</p>
			</div>
		</div>
	</div>
</div>
