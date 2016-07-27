<?php
/**
 * Insert Resource Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.ResourceTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Resource', 'Model');
App::uses('User', 'Model');

class ResourceTask extends ModelTask {

	public $model = 'Resource';

/**
 * Execute the task
 * Overrides ModelTask by setting the current user from created_by for permissions.created_by to match
 *
 * @return void
 */
	public function execute() {
		$User = Common::getModel('User');
		$Model = Common::getModel($this->model);
		$data = $this->getData();

		// Set Db Connection according to what is provided in params.
		if(isset($this->params['connection']) && !empty($this->params['connection'])) {
			$Model->useDbConfig = $this->params['connection'];
		}

		foreach ($data as $item) {
			// the 'owner' entry for permission.created_by will matching the resource.created_by
			$user = $User->find('first', ['conditions' => ['User.id' => $item['Resource']['created_by']]]);
			User::setActive($user);
			$this->insertItem($item, $Model);
		}
	}

	protected function getData() {
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.utest1-pwd1'),
			'name' => 'utest1-pwd1',
			'username' => 'unitTest1',
			'expiry_date' => null,
			'uri' => 'https://unit-test.com',
			'description' => 'description',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.kathleen'),
			'modified_by' => Common::uuid('user.id.kathleen')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.facebook-account'),
			'name' => 'facebook account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://facebook.com',
			'description' => 'facebook account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.irene'),
			'modified_by' => Common::uuid('user.id.dame')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.bank-password'),
			'name' => 'bank password',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://bank.com',
			'description' => 'bank password description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.irene'),
			'modified_by' => Common::uuid('user.id.dame')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.salesforce-account'),
			'name' => 'salesforce account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://salesforce.com',
			'description' => 'salesforce account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.marlyn')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.tetris-license'),
			'name' => 'tetris license',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://tetris.com',
			'description' => 'tetris license description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.marlyn')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.cpp1-pwd1'),
			'name' => 'cpp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.cpp1-pwd2'),
			'name' => 'cpp1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'cpp1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.lynne'),
			'modified_by' => Common::uuid('user.id.ada')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.cpp2-pwd1'),
			'name' => 'cpp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.cpp2-pwd2'),
			'name' => 'cpp2-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.dp1-pwd1'),
			'name' => 'dp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.lynne'),
			'modified_by' => Common::uuid('user.id.ada')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.dp2-pwd1'),
			'name' => 'dp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.lynne'),
			'modified_by' => Common::uuid('user.id.ada')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.op1-pwd2'),
			'name' => 'op1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.shared-resource'),
			'name' => 'shared resource',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://shared.resource.net/',
			'description' => 'shared resource description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.dame'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		$r[] = ['Resource' => [
			'id' => Common::uuid('resource.id.op1-pwd1'),
			'name' => 'op1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.ada'),
			'modified_by' => Common::uuid('user.id.lynne')
		]];
		return $r;
	}
}
