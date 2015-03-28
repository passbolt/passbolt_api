<ul class="navigation">
	<li><?php
		$class = ($this->params['controller'] == 'api_classes') ? array('class' => 'on') : null;
		echo $this->Html->link(__d('api_generator', 'Classes'), array(
			'plugin' => 'api_generator',
			'controller' => 'api_classes', 'action' => 'index'
			), $class);?>
	</li>
	<li><?php
		$class = ($this->action == 'source') ? array('class' => 'on') : null;
		echo $this->Html->link(__d('api_generator', 'Source'), array(
			'plugin' => 'api_generator',
			'controller' => 'api_files', 'action' => 'source'
			), $class);?>
	</li>
	<li><?php
		$class = ($this->action == 'files') ? array('class' => 'on') : null;
		echo $this->Html->link(__d('api_generator', 'Files'), array(
			'plugin' => 'api_generator',
			'controller' => 'api_files', 'action' => 'files'
			), $class);?>
	</li>
	<li><?php
		$class = ($this->params['controller'] == 'api_packages') ? array('class' => 'on') : null;
		echo $this->Html->link(__d('api_generator', 'Packages'), array(
			'plugin' => 'api_generator',
			'controller' => 'api_packages', 'action' => 'index'
			), $class);?>
	</li>
</ul>