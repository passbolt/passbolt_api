<?php
/**
 * JSon layout
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?><?php
	echo $this->fetch('content');

	if(isset($this->request->query['debug']) && $this->request->query['debug'] && Configure::read('debug') > 1) {
		var_dump($this->viewVars['flashMessages']);
		echo $this->element('sql_dump');
	}
?>