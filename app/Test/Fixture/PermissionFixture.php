<?php
/**
 * Permission Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.PermissionFixture
 * @since       version 2.12.9
 */
require_once (APP . 'Config' . DS . 'Schema' . DS . 'permissions.php');

class PermissionFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Permission';

	public function init() {
		$this->records = array(
			array('id' => '50bda571-2ee0-4416-aee2-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-6fa4-4abd-9be5-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-6cec-45df-a6d1-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-3a78-4a3b-84e7-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f320-4a69-86d2-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-db78-420e-a3e5-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-5838-4440-a1b2-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-4d54-4910-acd2-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-6cec-45df-a6d1-a7c58cebc04d','type' => '7','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-5f20-41b9-8071-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f320-4a69-86d2-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-3a00-4788-86f0-a7c58cebc04d','type' => '7','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-66c4-474a-a4a3-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f300-4729-b9bb-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-32e4-4b30-ba9a-a7c58cebc04d','type' => '15','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-7558-4533-aa65-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-261c-4ba1-99f8-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-be50-4553-9920-a7c58cebc04d','type' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-83b8-47cf-b332-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-8454-4117-a8f2-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-9b40-4c93-b738-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-9f78-4b60-adf3-a7c58cebc04d','aco' => 'Resource','aco_foreign_key' => '50bda570-1164-40ee-90d7-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-be50-4553-9920-a7c58cebc04d','type' => '7','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-b820-4306-9400-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-fb70-4cfc-9d18-a7c58cebc04d','aro' => 'User','aro_foreign_key' => '50bda571-1e18-4695-aa20-a7c58cebc04d','type' => '15','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-c0c8-4a03-98fe-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f8cc-4b70-850c-a7c58cebc04d','aro' => 'User','aro_foreign_key' => '50bda571-3534-4e98-864a-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-df14-4d83-8052-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-261c-4ba1-99f8-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-1268-456d-b792-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-ec4c-44f4-9928-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f724-4a09-90f6-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-be50-4553-9920-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-fc5c-4eb3-94f0-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-f870-44e0-b787-a7c58cebc04d','aro' => 'Group','aro_foreign_key' => '50bda571-e2e8-4e57-bce2-a7c58cebc04d','type' => '1','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-fff0-4cd9-9258-a7c58cebc04d','aco' => 'Category','aco_foreign_key' => '50bda570-d59c-4cec-9b9c-a7c58cebc04d','aro' => 'User','aro_foreign_key' => '50bda571-1e18-4695-aa20-a7c58cebc04d','type' => '15','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}

	public function insert() {
		$views = PermissionSchema::getViewsSQL();
		$functions = PermissionSchema::getFunctionsSQL();
		$this->Permission = new Permission();
		$this->Permission->useDbConfig = 'test';
		foreach ($functions as $function) {
			$this->Permission->query($function);
		}
		foreach ($views as $view) {
			$this->Permission->query($view);
		}
		$model = new Permission(null, null, 'test');
		$this->db = $model->getDataSource();
		parent::insert($this->db);
	}
}
