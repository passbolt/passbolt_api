<?php
/**
 * JSon layout
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.View.Layouts
 * @since         version 2.12.6
 * @license       http://www.passbolt.com/license
 */
?><?php
	echo $this->fetch('content');

	if(isset($this->request->query['debug']) && $this->request->query['debug'] && Configure::read('debug') > 1) {
		echo $this->element('sql_dump');
	}
?>