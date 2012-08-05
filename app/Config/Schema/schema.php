<?php 
App::uses('Category', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

class AppSchema extends CakeSchema {

	public function before($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}

	public function after($event = array()) {
		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'categories':
					$category = ClassRegistry::init('Category');
					$category->create();
					$projects = $category->save(array('Category' =>array('name' => 'Projects')));
					$category->create();
					$category->save(array('Category' =>array('name' => 'Administration')));
					$category->create();
					$category->save(array('Category' =>array('name' => 'Management')));
					$category->create();
					$cakephp = $category->save(array('Category' =>array('name' => 'CakePHP', 'parent_id' => $projects['Category']['id'])));
					$category->create();
					$category->save(array('Category' =>array('name' => 'Drupal', 'parent_id' => $projects['Category']['id'])));
					$category->create();
					$category->save(array('Category' =>array('name' => 'Magento', 'parent_id' => $projects['Category']['id'])));
					$category->create();
					$category->save(array('Category' =>array('name' => 'cakephp-project1', 'parent_id' => $cakephp['Category']['id'])));
					$category->create();
					$category->save(array('Category' =>array('name' => 'cakephp-project2', 'parent_id' => $cakephp['Category']['id'])));
				break;
				case 'users':
					$user = ClassRegistry::init('User');
					$user->create();
					$user->save(array('User'=>array('id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'username' => 'anonymous@passbolt.com', 'password' => NULL, 'active' => 1, 'created' => '2012-07-04 13:45:11', 'modified' => '2012-07-04 13:45:14', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')));
				break;
				case 'roles':
					$role = ClassRegistry::init('Role');
					$role->create();
					$role->save(array('Role'=>array('id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'name' => 'guest', 'description' => 'Non logged-in user', 'created' => '2012-07-04 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')));
					$role->create();
					$role->save(array('Role'=>array('id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c', 'name' => 'user', 'description' => 'Logged in default user', 'created' => '2012-07-04 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')));
					$role->create();
					$role->save(array('Role'=>array('id' => '142c1188-c5cd-11e1-a0c5-080027796c4c', 'name' => 'admin', 'description' => 'Organization administrator', 'created' => '2012-07-04 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')));
					$role->create();
					$role->save(array('Role'=>array('id' => '142c1340-c5cd-11e1-a0c5-080027796c4c', 'name' => 'root', 'description' => 'Super Administrator', 'created' => '2012-07-04 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')));
				break;
			}	
		}
	}

	public $categories = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_type_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'comment' => 'type id of the category', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $category_types = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $resources = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expiry_date' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'uri' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $roles = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $users = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'role_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
}
