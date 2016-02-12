<?php
/**
 * Bottom page scripts
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
	echo $this->fetch('scriptBottom');
	if (!Configure::read('debug')) {
		echo $this->element('analytics/piwik');
	}
?>
