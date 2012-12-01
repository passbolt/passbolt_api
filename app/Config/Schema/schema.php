<?php 
/**
 * Schema
 * $ ./Console/cake schema create
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.schema
 * @since        version 2.12.7
 */
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('PermissionType', 'Model');

class AppSchema extends CakeSchema {

	public static $created = array();

	public function before($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}

	public function insertCategories ($categories, $parentCategory=null) {
		foreach ($categories as $categoryId => $subCategories) {
			// Insert Category
			if ($categoryId != 'Resources') {
				$this->Category->create();
				$category = $this->Category->save(array(
					'Category' => array(
						'name' => $categoryId,
						'parent_id' => isset($parentCategory) ? $parentCategory['Category']['id'] : null
					)
				));
				$this->insertCategories ($subCategories, $category);
			} else {
				$resources = $subCategories;
				foreach ($resources as $value) {
					$resource = null;
					if (!($resource = $this->Resource->findByName($value['Resource']['name']))) {
						$this->Resource->create();
						$resource = $this->Resource->save($value);
					}
					$this->CategoryResource->create();
					$this->CategoryResource->save(array(
						'CategoryResource' => array( 'category_id' => $parentCategory['Category']['id'], 'resource_id' => $resource['Resource']['id'] )
					));
				}
			}
		}
	}

	public function insertGroups ($groups) {
		foreach ($groups as $groupName => $users) {
			// Insert group
			if ($groupName != 'Users') {
				$this->Group->create();
				$group = $this->Group->save(array(
					'Group' => array(
						'name' => $groupName
					)
				));
			}

			foreach ($users['Users'] as $value) {
				if (!($user = $this->User->findByUsername($value['User']['username']))) {
					$this->User->create();
					$user = $this->User->save($value);
				}
				if ($groupName != 'Users') {
					$this->GroupUser->create();
					$this->GroupUser->save(array(
						'GroupUser' => array( 'group_id' => $group['Group']['id'], 'user_id' => $user['User']['id'] )
					));
				}
			}
		}
	}

	public function createPermissionCacheView() {
		$permission = ClassRegistry::init('Permission');
		$permission->query(
			"
			CREATE OR REPLACE ALGORITHM=UNDEFINED 
			SQL SECURITY DEFINER VIEW `aro_aco_permissions` 
			AS 
			  (
			    SELECT 'Category' COLLATE utf8_unicode_ci AS `aco`,`c`.`id` AS `aco_foreign_key`,'Group' COLLATE utf8_unicode_ci AS `aro`,`g`.`id` AS `aro_foreign_key`,`getPermissions`('Group',`g`.`id`,'Category',`c`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
			    FROM (`categories` `c` join `groups` `g`)
			  ) 
		  UNION (
		      SELECT 'Category' COLLATE utf8_unicode_ci AS `aco`,`c`.`id` AS `aco_foreign_key`,'User' COLLATE utf8_unicode_ci AS `aro`,`u`.`id` AS `aro_foreign_key`,`getPermissions`('User',`u`.`id`,'Category',`c`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
		      FROM (`categories` `c` join `users` `u`)
		    ) 
		  UNION (
		      SELECT 'Resource' COLLATE utf8_unicode_ci AS `aco`,`r`.`id` AS `aco_foreign_key`,'Group' COLLATE utf8_unicode_ci AS `aro`,`g`.`id` AS `aro_foreign_key`,`getPermissions`('Group',`g`.`id`,'Resource',`r`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
		      FROM (`resources` `r` join `groups` `g`)
			  ) 
		  UNION (
		      SELECT 'Resource' COLLATE utf8_unicode_ci AS `aco`,`r`.`id` AS `aco_foreign_key`,'User' COLLATE utf8_unicode_ci AS `aro`,`u`.`id` AS `aro_foreign_key`,`getPermissions`('User',`u`.`id`,'Resource',`r`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
		      FROM (`resources` `r` join `users` `u`)
			  );"
		);
		$permission->query(
			"
			CREATE OR REPLACE ALGORITHM=UNDEFINED 
			SQL SECURITY DEFINER VIEW `permissions_cache` 
			AS
			SELECT `aap`.aco, `aap`.aco_foreign_key, `aap`.aro, `aap`.aro_foreign_key, 
			case 
	        when  `p`.type IS NULL then " . PermissionType::DENY . "
	        when  `p`.type IS NOT NULL then `p`.type
	    end as type,
			case 
	        when  `p`.type IS NULL then 1
	        when  `p`.type IS NOT NULL then 0
	    end as inherited,
			`p`.created, `p`.modified, `p`.created_by, `p`.modified_by
			FROM aro_aco_permissions aap
			LEFT JOIN permissions p ON `p`.id = `aap`.permission_id
			LEFT JOIN permissions_types pt ON `pt`.serial = `p`.type
			");
	}

	public function createPermissionFunctions() {
		$permission = ClassRegistry::init('Permission');
		$getGroupCategoryPermission = "
		  DROP FUNCTION IF EXISTS getGroupCategoryPermission;
			CREATE FUNCTION `getGroupCategoryPermission`(`group_id` VARCHAR(36), `category_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
			    NO SQL
					BEGIN
					    DECLARE `permid` VARCHAR(36);
					    DECLARE `catleft` INT(10);
					    DECLARE `catright` INT(10);
					    
					    SELECT `c`.lft, `c`.rght INTO `catleft`, `catright`
					    FROM categories c 
					    WHERE `c`.id = `category_id`;
					    
					    SELECT `p`.id INTO `permid`
					    FROM `passbolt`.permissions p
					    INNER JOIN (
					            SELECT * FROM `categories` c 
					            WHERE `c`.lft <= `catleft` 
					            AND `c`.rght >= `catright` 
					            ORDER BY `c`.lft DESC
					            ) viewcat 
					    ON `p`.aco_foreign_key=`viewcat`.id 
					    INNER JOIN groups g ON `p`.aro_foreign_key=`g`.id 
					    WHERE `p`.aro='Group'
					    AND `p`.aco='Category'
					    AND `p`.aro_foreign_key = `group_id` 
					    ORDER BY `viewcat`.lft DESC
					    LIMIT 1;
					    RETURN `permid`;
					END;";
		$permission->query($getGroupCategoryPermission);

		$getGroupResourcePermission = "
			DROP FUNCTION IF EXISTS getGroupResourcePermission;
			CREATE FUNCTION `getGroupResourcePermission`(`group_id` VARCHAR(36), `resource_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
		    NO SQL
				BEGIN
				    DECLARE `permid` VARCHAR(36);
				    SELECT `p`.id INTO `permid`
				    FROM `passbolt`.permissions p 
				    WHERE `p`.aro='Group'
				    AND `p`.aco='Resource'
				    AND `p`.aco_foreign_key = `resource_id`
				    AND `p`.aro_foreign_key = `group_id` 
				    LIMIT 1;
				    IF (`permid` IS NULL) THEN
				      SELECT `p`.id INTO `permid` FROM(
				      	SELECT `passbolt`.getGroupCategoryPermission(`group_id`, `cr`.category_id) COLLATE utf8_unicode_ci AS `permid`
				        FROM categories_resources `cr`
				        WHERE `cr`.resource_id = `resource_id`
				      ) `catview`
				      INNER JOIN permissions p ON `p`.id=`catview`.permid
				      ORDER BY `p`.type DESC
				      LIMIT 1;
				      RETURN `permid`;
				    END IF;
				    RETURN `permid`;
				END;";
			$permission->query($getGroupResourcePermission);

			$getUserCategoryPermission = "
				DROP FUNCTION IF EXISTS getUserCategoryPermission;
				CREATE FUNCTION `getUserCategoryPermission`(`user_id` VARCHAR(36), `category_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
				NO SQL
				BEGIN
			  DECLARE `permid` VARCHAR(36);
			  SELECT `p`.id INTO `permid`
			  FROM `passbolt`.permissions p 
			  WHERE `p`.aro='User'
			  AND `p`.aco='Category'
			  AND `p`.aco_foreign_key = `category_id`
			  AND `p`.aro_foreign_key = `user_id` 
			  LIMIT 1;
			  IF (`permid` IS NULL) THEN
			      SELECT `p`.id  INTO `permid`  
			      FROM(
			        SELECT getGroupCategoryPermission(`gu`.group_id, `category_id`) COLLATE utf8_unicode_ci AS `permid`
			        FROM groups_users `gu` 
			        WHERE `gu`.user_id = `user_id`
			      ) `groupview`
			      INNER JOIN permissions `p` ON `p`.id=`groupview`.permid
			      ORDER BY `p`.type DESC
				    LIMIT 1;
			      RETURN `permid`;
			  END IF;
			  RETURN `permid`;
			END;";
			$permission->query($getUserCategoryPermission);

			// TODO : manage case where user is owner of the resource. What to do ? What should be the permission then ?
			$getUserResourcePermission = "
				DROP FUNCTION IF EXISTS getUserResourcePermission;
				CREATE FUNCTION `getUserResourcePermission`(`user_id` VARCHAR(36), `resource_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
				NO SQL
				BEGIN
				  DECLARE `permid` VARCHAR(36);
				  SELECT `p`.id INTO `permid`
				  FROM `passbolt`.permissions p 
				  WHERE `p`.aro='User'
				  AND `p`.aco='Resource'
				  AND `p`.aco_foreign_key = `resource_id`
				  AND `p`.aro_foreign_key = `user_id` 
				  LIMIT 1;
				  IF (`permid` IS NULL) THEN
					  SELECT `p`.id INTO `permid` 
					  FROM(
					      (SELECT getGroupResourcePermission(`gu`.group_id, `resource_id`) COLLATE utf8_unicode_ci AS `permid`
						    FROM groups_users `gu` 
						    WHERE `gu`.user_id = `user_id`)
						    UNION
						    (SELECT getUserCategoryPermission(`user_id`, cr.`category_id`) COLLATE utf8_unicode_ci AS `permid`
						    FROM categories_resources `cr` 
						    WHERE `cr`.resource_id = `resource_id`)
					  ) `groupview`
					  INNER JOIN permissions `p` ON `p`.id=`groupview`.permid
					  ORDER BY `p`.type DESC
				    LIMIT 1;
					  RETURN `permid`;
					END IF;
					RETURN `permid`;
				END;";
			$permission->query($getUserResourcePermission);

			$getPermissions = "
				DROP FUNCTION IF EXISTS getPermissions;
				CREATE FUNCTION `getPermissions`(`aro` VARCHAR(50), `aro_foreign_key` VARCHAR(36), `aco` VARCHAR(50), `aco_foreign_key` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
				BEGIN
				    DECLARE `permid` VARCHAR(36);
				    IF(`aro` = 'Group' AND `aco` = 'Category') THEN
				    	SELECT getGroupCategoryPermission(`aro_foreign_key`, `aco_foreign_key`) COLLATE utf8_unicode_ci
				        INTO `permid`;
				    ELSEIF(`aro` = 'Group' AND `aco` = 'Resource') THEN
				    	SELECT getGroupResourcePermission(`aro_foreign_key`, `aco_foreign_key`) COLLATE utf8_unicode_ci
				        INTO `permid`;
				    ELSEIF(`aro` = 'User' AND `aco` = 'Category') THEN
				    	SELECT getUserCategoryPermission(`aro_foreign_key`, `aco_foreign_key`) COLLATE utf8_unicode_ci
				        INTO `permid`;
				    ELSEIF(`aro` = 'User' AND `aco` = 'Resource') THEN
				    	SELECT getUserResourcePermission(`aro_foreign_key`, `aco_foreign_key`) COLLATE utf8_unicode_ci
				        INTO `permid`;
				    END IF;
				    RETURN `permid`;
				END;";
			$permission->query($getPermissions);
	}

	public function after($event = array()) {
		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'categories':
					array_push(self::$created, 'categories');
					if (in_array('resources', self::$created)) {
						$this->Category = ClassRegistry::init('Category');
						$this->Resource = ClassRegistry::init('Resource');
						$this->CategoryResource = ClassRegistry::init('CategoryResource');
						$this->insertCategories($this->_getDefaultCategories());
					}
				break;

				case 'category_types' :
					array_push(self::$created, 'category_types');
					$categoryType = ClassRegistry::init('CategoryType');
					$type['name'] = "default";
					$categoryType->create();
					$categoryType->save($type);
					$type['name'] = "database";
					$categoryType->create();
					$categoryType->save($type);
					$type['name'] = "ssh";
					$categoryType->create();
					$categoryType->save($type);
					break;

				case 'users':
					array_push(self::$created, 'users');
					$user = ClassRegistry::init('User');
					$us = $this->_getDefaultUsers();
					foreach ($us as $u) {
						$user->create();
						$user->save($u);
					}
				break;

				case 'resources':
					array_push(self::$created, 'resources');
					if (in_array('categories', self::$created)) {
						$this->Category = ClassRegistry::init('Category');
						$this->Resource = ClassRegistry::init('Resource');
						$this->CategoryResource = ClassRegistry::init('CategoryResource');
						$this->insertCategories($this->_getDefaultCategories());
					}
				break;

				case 'groups':
					array_push(self::$created, 'groups');
					if (in_array('groups', self::$created)) {
						$this->Group = ClassRegistry::init('Group');
						$this->User = ClassRegistry::init('User');
						$this->GroupUser = ClassRegistry::init('GroupUser');
						$this->insertGroups($this->_getDefaultGroups());
					}
				break;

				case 'permissions':
					array_push(self::$created, 'permissions');
					$permission = ClassRegistry::init('Permission');
					$ps = $this->_getDefaultPermissions();
					foreach ($ps as $p) {
						$permission->create();
						$permission->save($p);
					}
					// create necessary mysql functions
					$this->createPermissionFunctions();
					// Create the permissions_cache view
					$this->createPermissionCacheView();
				break;

				case 'permissions_types':
					array_push(self::$created, 'permissions_types');
					$permissionType = ClassRegistry::init('PermissionType');
					$pts = $this->_getDefaultPermissionTypes();
					foreach ($pts as $pt) {
						$permissionType->create();
						$permissionType->save($pt);
					}
				break;

				case 'roles':
					array_push(self::$created, 'roles');
					$role = ClassRegistry::init('Role');
					$rs = $this->_getDefaultRoles();
					foreach ($rs as $r) {
						$role->create();
						$role->save($r);
					}
				break;

				case 'tags':
					array_push(self::$created, 'tags');
					$tag = ClassRegistry::init('Tag');
					$ts = $this->_getDefaultTags();
					foreach ($ts as $t) {
						$tag->create();
						$tag->save($t);
					}
				break;

				case 'tags_resources':
					array_push(self::$created, 'tags_resources');
					$tagResource = ClassRegistry::init('TagResource');
					$rts = $this->_getDefaultResourceTags();
					foreach ($rts as $rt) {
						$tagResource->create();
						$tagResource->save($rt);
					}
				break;
			}
		}
	}

	public $roles = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'role_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

 	public $category_types = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $resources = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expiry_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'uri' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $secrets = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $categories_resources = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'category_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'category_id' => array('column' => array('category_id', 'resource_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $categories = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_type_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'comment' => 'type id of the category', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $groups_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'group_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => array('user_id', 'group_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $groups = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $gpgKeys = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4096, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bits' => array('type' => 'integer', 'null' => false, 'default' => '2048', 'length' => 11),
		'uid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128),
		'key_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 8),
		'fingerprint' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 51),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'RSA', 'length' => 16),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'key_id' => array('column' => 'key_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	// TODO : should become permission_types
	public $permissions_types = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'serial' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'binary' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'_admin' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 1),
		'_update' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 1),
		'_create' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 1),
		'_read' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 1),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'SERIAL' => array('column' => 'serial', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $permissions = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aco' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aco_foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aro' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aro_foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $tags = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $tags_resources = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'tag_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'tag_id' => array('column' => array('tag_id', 'resource_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	protected function _getDefaultCategories() {
		$categories = array (
			'Bolt Softwares Pvt. Ltd.' => array(
				'administration' => array(
					'accounts' => array(
						'Resources' => array(
							array('Resource' => array('id' => '509bb871-b964-48ab-94fe-fb098cebc04d','name' => 'bank password', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'marketing' => array(
						'Resources' => array(
							array('Resource' => array('id' => '509bb871-5168-49d4-a676-fb098cebc04d', 'name' => 'facebook account', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'hr' => array(
						'Resources' => array(
							array('Resource' => array('name' => 'salesforce account', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'misc' => array(
						'Resources' => array(
							array('Resource' => array('name' => 'tetris license', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					)
				),
				'projects' => array(
					'cakephp' => array(
						'cp-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'cpp1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'cpp1-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'cp-project2' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'cpp2-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'cpp2-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'cp-project3' => array()
					),
					'drupal' => array(
						'd-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'dp1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
							)
						),
						'd-project2' => array()
					),
					'others' => array(
						'o-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'op1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'op1-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'shared resource', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'o-project2' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'shared resource')),
							)
						)
					)
				),
			)
		);
		return $categories;
	}

	protected function _getDefaultGroups() {
		$userRoleId = '0208f57a-c5cd-11e1-a0c5-080027796c4c';
		$defaultPassword = 'test123';
		$categories = array (
			'management' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'dark.vador@test.com', 'password' => $defaultPassword, 'active' => '1')),
				)
			),
			'accounting dpt' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'aurelie.gerhards@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
			'human resources' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'ismail.guennouni@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'myriam.djerouni@test.com', 'password' => $defaultPassword, 'active' => '1'))
				),
			),
			'developers' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'remy.bertot@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'cedric.alfonsi@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'kevin.muller@test.com', 'password' => $defaultPassword, 'active' => '1'))
				),
			),
			'developers team leads' => array(
				'Users' => array(
					array('User' => array( 'username' => 'remy.bertot@test.com'))
				),
			),
			'developers drupal' => array(
				'Users' => array(
					array('User' => array( 'username' => 'cedric.alfonsi@test.com')),
					array('User' => array( 'username' => 'kevin.muller@test.com'))
				),
			),
			'developers cakephp' => array(
				'Users' => array(
					array('User' => array( 'username' => 'remy.bertot@test.com'))
				),
			),
			'freelancers' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'a-user1@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'a-user2@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'b-user1@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'b-user2@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
			'company a' => array(
				'Users' => array(
					array('User' => array('username' => 'a-user1@test.com')),
					array('User' => array('username' => 'a-user2@test.com')),
				),
			),
			'company b' => array(
				'Users' => array(
					array('User' => array('username' => 'b-user1@test.com')),
					array('User' => array('username' => 'b-user2@test.com')),
				),
			),
			'Users' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'jean-rene@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'adminsys@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'ramesh.kumar@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
		);
		return $categories;
	}

	protected function _getDefaultUsers() {
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'we are legions',
			'active' => 1,
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'username' => 'test@passbolt.com',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1,
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		return $us;
	}

	protected function _getDefaultPermissions() {
		$cDrupal = $this->Category->findByName("drupal");
		$cAdministration = $this->Category->findByName("administration");
		$cAccounts = $this->Category->findByName("accounts");
		$gDrupal = $this->Group->findByName("developers drupal");
		$gAdministration = $this->Group->findByName("accounting dpt");

		$gManagement = $this->Group->findByName("management");
		$cRoot = $this->Category->findByName("Bolt Softwares Pvt. Ltd.");
		// Group Management has admin rights on everything
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cRoot['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gManagement['Group']['id'],
			'type' => PermissionType::ADMIN,
		));

		$gHumanResources = $this->Group->findByName("human resources");
		$cAdministration = $this->Category->findByName("administration");
		// Group human resources have read only rights on administration
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cAdministration['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gHumanResources['Group']['id'],
			'type' => PermissionType::READ
		));
		// human resources have no rights on accounts
		$cAccounts = $this->Category->findByName("accounts");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cAccounts['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gHumanResources['Group']['id'],
			'type' => PermissionType::DENY
		));
		// Group human resources can modify resource salesforce account
		$rSalesforce = $this->Resource->findByName("salesforce account");
		$ps[] = array('Permission' => array(
			'aco' => 'Resource',
			'aco_foreign_key' => $rSalesforce['Resource']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gHumanResources['Group']['id'],
			'type' => PermissionType::UPDATE
		));

		// accounting dpt can access administration>accounts in read only
		$gAccountingDpt = $this->Group->findByName("accounting dpt");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cAccounts['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gAccountingDpt['Group']['id'],
			'type' => PermissionType::READ,
		));

		$gDrupal = $this->Group->findByName("developers drupal");
		$cDrupal = $this->Category->findByName("drupal");
		// Group developers drupal have read only rights on Projects > Drupal
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cDrupal['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gDrupal['Group']['id'],
			'type' => PermissionType::READ,
		));
		// Group cakephp has access to category cakephp in readonly
		$cCakephp = $this->Category->findByName("cakephp");
		$gCakephp = $this->Group->findByName("developers cakephp");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cCakephp['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gCakephp['Group']['id'],
			'type' => PermissionType::READ,
		));
		// Group developers team leads has access to projects in modify
		$cProjects = $this->Category->findByName("projects");
		$gTeamleads = $this->Group->findByName("developers team leads");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cProjects['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gTeamleads['Group']['id'],
			'type' => PermissionType::UPDATE,
		));
		// Remy Bertot has admin rights on cp-project1
		$cProject1 = $this->Category->findByName("cp-project1");
		$uRemy = $this->User->findByUsername("remy.bertot@test.com");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cProject1['Category']['id'],
			'aro' => 'User',
			'aro_foreign_key' => $uRemy['User']['id'],
			'type' => PermissionType::ADMIN,
		));

		// Remy Bertot has admin rights on others
		$cOthers = $this->Category->findByName("others");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cOthers['Category']['id'],
			'aro' => 'User',
			'aro_foreign_key' => $uRemy['User']['id'],
			'type' => PermissionType::ADMIN,
		));

		//  Freelancers have read only rights to projects others
		$gFreelancers = $this->Group->findByName("freelancers");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cProjects['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gFreelancers['Group']['id'],
			'type' => PermissionType::READ,
		));

		// Jean René has readonly access rights on cp-project2
		$cCpProject2 = $this->Category->findByName("cp-project2");
		$uJeanrene = $this->User->findByUsername("jean-rene@test.com");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cCpProject2['Category']['id'],
			'aro' => 'User',
			'aro_foreign_key' => $uJeanrene['User']['id'],
			'type' => PermissionType::READ,
		));

		//  company a has read only rights to o-project1
		$gCompanya = $this->Group->findByName("company a");
		$cOproject1 = $this->Category->findByName("o-project1");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cOproject1['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gCompanya['Group']['id'],
			'type' => PermissionType::READ,
		));

		//  company a has read only rights to o-project1
		$cOproject2 = $this->Category->findByName("o-project2");
		$ps[] = array('Permission' => array(
			'aco' => 'Category',
			'aco_foreign_key' => $cOproject2['Category']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $gCompanya['Group']['id'],
			'type' => PermissionType::UPDATE,
		));

		return $ps;
	}

	protected function _getDefaultPermissionTypes() {
		$pds = array();
		$pds[] = array(
			'serial' => 0,
			'name' => '----',
			'binary' => '0000',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 1,
			'binary' => '0001',
			'name' => '---r',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 2,
			'binary' => '0010',
			'name' => '--c-',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 3,
			'binary' => '0011',
			'name' => '--cr',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 4,
			'binary' => '0100',
			'name' => '-u--',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 5,
			'binary' => '0101',
			'name' => '-u-r',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 6,
			'binary' => '0110',
			'name' => '-uc-',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 7,
			'binary' => '0111',
			'name' => '-ucr',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 8,
			'binary' => '1000',
			'name' => 'a---',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 9,
			'binary' => '1001',
			'name' => 'a--r',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 10,
			'binary' => '1010',
			'name' => 'a-c-',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 11,
			'binary' => '1011',
			'name' => 'a-cr',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 12,
			'binary' => '1100',
			'name' => 'au--',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 13,
			'binary' => '1101',
			'name' => 'au-r',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 14,
			'binary' => '1110',
			'name' => 'auc-',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 15,
			'binary' => '1111',
			'name' => 'aucr',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);

		return $pds;
	}

	protected function _getDefaultRoles() {
		$rs[] = array('Role' => array(
			'id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'guest',
			'description' => 'Non logged-in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'user',
			'description' => 'Logged in default user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'admin',
			'description' => 'Organization administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'root',
			'description' => 'Super Administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rs;
	}

	protected function _getDefaultTags() {
		$ts[] = array('Tag' => array(
			'id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'social',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'facebook',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00002-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'twitter',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'banking',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $ts;
	}

	protected function _getDefaultResourceTags() {
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00001-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',      // banking
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d', // bank
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00002-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',      // facebook
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00003-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',      // social
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rts;
	}
}
