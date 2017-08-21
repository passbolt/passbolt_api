<?php
/**
 * Gpgkey Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

require_once(APP . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');
App::uses('Gpg', 'Model/Utility');
App::uses('GpgkeyTask', 'DataDefault.Console/Command/Task');

class LargeGpgkeyTask extends GpgkeyTask {

	public $validateOnSave = false;

}
