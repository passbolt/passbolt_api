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

class ResourceTask extends ModelTask {

	public $model = 'Resource';

	public function execute() {
		$Model = ClassRegistry::init($this->model);

		// Disable the trackable behavior to be able to use custom created_by and modified_by values.
		$Model->Behaviors->disable('Trackable');
		// Retrieve the data to insert.
		$data = $this->getData();

		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save();
			if (!$instance) {
				$this->out('<error>Unable to insert ' . $item[$this->model]['name'] . '</error>');
			}
		}
	}

	protected function getData() {
		$r[] = array('Resource'=>array(
			'id' => Common::uuid('resource.id.utest1-pwd1'),
			'name' => 'utest1-pwd1',
			'username' => 'unitTest1',
			'expiry_date' => null,
			'uri' => 'https://unit-test.com',
			'description' => 'description',
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.kathleen'),
			'modified_by' => Common::uuid('user.id.kathleen')
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));		
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		$r[] = array('Resource'=>array(
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
		));
		return $r;
	}
}
