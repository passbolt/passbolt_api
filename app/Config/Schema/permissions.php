<?php
/**
 * Permission Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.permission
 * @since        version 2.12.11
 */
App::uses('Category', 'Model');
App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');

class PermissionSchema {

	public function init() {
		$permission = ClassRegistry::init('Permission');
		$ps = $this->_getDefaultPermissions();
		foreach ($ps as $p) {
			$permission->create();
			$permission->save($p);
		}
		// Create the permissions_cache functiona and view
		$this->createFunctions();
		$this->createViews();
	}

	protected function _getDefaultPermissions() {
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');

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

	public static function getViewsSQL() {
		return array(
			"categories_parents" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW categories_parents AS
					SELECT c.id AS child_id, c.lft AS child_lft, c.rght AS child_rght, cp.id AS id, cp.lft AS lft, cp.rght AS rght
					FROM categories c
					INNER JOIN categories cp ON cp.lft <= c.lft AND cp.rght >= c.rght;
			",
			"groups_categories_permissions" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW groups_categories_permissions AS
					SELECT `g`.id AS group_id, `cp`.child_id AS category_id, `p`.id AS permission_id
					FROM permissions p
					LEFT JOIN `categories_parents` cp ON `p`.aco_foreign_key=`cp`.id
					INNER JOIN `groups` g ON `p`.aro_foreign_key=`g`.id 
					WHERE `p`.aro = 'Group'
					AND `p`.aco = 'Category'
					AND `p`.aro_foreign_key = `g`.id
					ORDER BY `g`.id, `cp`.child_id, `cp`.lft DESC
			",	
			"groups_resources_permissions_tmp" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW groups_resources_permissions_tmp AS
					SELECT `g`.id AS group_id, `r`.id AS resource_id,
						(SELECT `p`.id
							FROM permissions p 
							WHERE `p`.aro='Group'
							AND `p`.aco='Resource'
							AND `p`.aco_foreign_key = `r`.id
							AND `p`.aro_foreign_key = `g`.id 
							LIMIT 1
						) AS direct_permission_id,
						(SELECT `p`.id
							FROM `permissions` p,
								`groups_categories_permissions` gcp,
								`categories_resources` cr
							WHERE `gcp`.group_id = `g`.id
							AND `gcp`.permission_id = `p`.id
							AND `gcp`.category_id = `cr`.category_id
							AND `cr`.resource_id = `r`.id
							ORDER BY `p`.type DESC
							LIMIT 1
						) AS inherited_permission_id
					FROM `groups` g, `resources` r
			",
			"groups_resources_permissions" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW groups_resources_permissions AS
					SELECT `g`.id AS group_id, `r`.id AS resource_id, IFNULL(`grp_tmp`.direct_permission_id, `grp_tmp`.inherited_permission_id) AS permission_id
					FROM `groups_resources_permissions_tmp` grp_tmp, `groups` g, `resources` r
					WHERE `grp_tmp`.group_id = `g`.id
					AND `grp_tmp`.resource_id = `r`.id
			",	
			"users_categories_permissions_tmp" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW users_categories_permissions_tmp AS
					SELECT `u`.id AS user_id, `c`.id AS category_id,
						(SELECT `p`.id
							FROM permissions p
							WHERE `p`.aro='User'
							AND `p`.aco='Category'
							AND `p`.aco_foreign_key = `c`.id
							AND `p`.aro_foreign_key = `u`.id
							LIMIT 1
						) AS direct_permission_id,
						(SELECT `p`.id
							FROM permissions `p`, 
								`groups_categories_permissions` gcp,
								`groups_users` gu 
							WHERE `gcp`.category_id = `c`.id
								AND `gu`.user_id = `u`.id
								AND `p`.id = `gcp`.permission_id
								AND `gu`.group_id = `gcp`.group_id
							ORDER BY `p`.type DESC
					    LIMIT 1
						) AS inherited_permission_id
					FROM `users` u, `categories` c
			",		
			"users_categories_permissions" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW users_categories_permissions AS
					SELECT 
						`u`.id AS user_id, 
						`c`.id AS category_id, 
						IFNULL(`ucp_tmp`.direct_permission_id, `ucp_tmp`.inherited_permission_id) AS permission_id
					FROM `users_categories_permissions_tmp` ucp_tmp, `users` u, `categories` c
					WHERE `ucp_tmp`.user_id = `u`.id
					AND `ucp_tmp`.category_id = `c`.id
			",
			"users_resources_permissions_tmp" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW users_resources_permissions_tmp AS
					SELECT 
						`u`.id AS user_id, 
						`r`.id AS resource_id,
						(SELECT `p`.id
							FROM permissions p
							WHERE `p`.aro='User'
								AND `p`.aco='Resource'
								AND `p`.aco_foreign_key = `r`.id
								AND `p`.aro_foreign_key = `u`.id
							LIMIT 1
						) AS direct_permission_id,
						(SELECT `grp`.permission_id AS `permid`
							FROM `groups_resources_permissions` grp,
								`groups_users` gu,
								`permissions` p
							WHERE `gu`.user_id = `u`.id
								AND `grp`.resource_id = `r`.id
								AND `grp`.group_id = `gu`.group_id
								AND `p`.id = `grp`.permission_id
							ORDER BY p.type DESC LIMIT 1
						) AS grp_permission_id,
						(SELECT `p`.type
							FROM `groups_resources_permissions` grp,
								`groups_users` gu,
								`permissions` p
							WHERE `gu`.user_id = `u`.id
								AND `grp`.resource_id = `r`.id
								AND `grp`.group_id = `gu`.group_id
								AND `p`.id = `grp`.permission_id
							ORDER BY p.type DESC LIMIT 1
						) AS grp_permission_type,
						(SELECT `ucp`.permission_id AS `permid`
							FROM `users_categories_permissions` ucp,
								`categories_resources` cr,
								`permissions` p
							WHERE `ucp`.user_id = `u`.id
								AND `cr`.resource_id = `r`.id
								AND `ucp`.category_id = `cr`.category_id
								AND `p`.id = `ucp`.permission_id
							ORDER BY p.type DESC LIMIT 1
						) AS ucp_permission_id,
						(SELECT `p`.type
							FROM `users_categories_permissions` ucp,
								`categories_resources` cr,
								`permissions` p
							WHERE `ucp`.user_id = `u`.id
								AND `cr`.resource_id = `r`.id
								AND `ucp`.category_id = `cr`.category_id
								AND `p`.id = `ucp`.permission_id
							ORDER BY p.type DESC LIMIT 1
						) AS ucp_permission_type
						
					FROM `users` u, `resources` r
			",
			"users_resources_permissions" => "
				CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW users_resources_permissions AS
				
					SELECT u.id AS user_id, r.id as resource_id, IFNULL(
						direct_permission_id,
						IF(
							grp_permission_type>ucp_permission_type,
							grp_permission_id,
							ucp_permission_id
						)
					) AS permission_id
					
					FROM `users` u, `resources` r, users_resources_permissions_tmp urp_tmp
					WHERE urp_tmp.user_id = u.id
					AND urp_tmp.resource_id = r.id
			",
			"aro_aco_permissions" =>
				"CREATE OR REPLACE ALGORITHM=UNDEFINED 
				SQL SECURITY DEFINER VIEW `aro_aco_permissions` 
				AS 
				  (
				    SELECT 'Category' COLLATE utf8_unicode_ci AS `aco`,`c`.`id` AS `aco_foreign_key`,'Group' COLLATE utf8_unicode_ci AS `aro`,`g`.`id` AS `aro_foreign_key`,`getPermissions`('Group',`g`.`id`,'Category',`c`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
				    FROM (`categories` `c` join `groups` `g`)
				  ) 
			  UNION ALL (
			      SELECT 'Category' COLLATE utf8_unicode_ci AS `aco`,`c`.`id` AS `aco_foreign_key`,'User' COLLATE utf8_unicode_ci AS `aro`,`u`.`id` AS `aro_foreign_key`,`getPermissions`('User',`u`.`id`,'Category',`c`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
			      FROM (`categories` `c` join `users` `u`)
			    ) 
			  UNION  ALL(
			      SELECT 'Resource' COLLATE utf8_unicode_ci AS `aco`,`r`.`id` AS `aco_foreign_key`,'Group' COLLATE utf8_unicode_ci AS `aro`,`g`.`id` AS `aro_foreign_key`,`getPermissions`('Group',`g`.`id`,'Resource',`r`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
			      FROM (`resources` `r` join `groups` `g`)
				  ) 
			  UNION ALL(
			      SELECT 'Resource' COLLATE utf8_unicode_ci AS `aco`,`r`.`id` AS `aco_foreign_key`,'User' COLLATE utf8_unicode_ci AS `aro`,`u`.`id` AS `aro_foreign_key`,`getPermissions`('User',`u`.`id`,'Resource',`r`.`id`) COLLATE utf8_unicode_ci AS `permission_id` 
			      FROM (`resources` `r` join `users` `u`)
				  );
			",
			"permissions_cache" =>
				"CREATE OR REPLACE ALGORITHM=UNDEFINED 
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
				"
			);
	}

	public function createViews() {
		$permission = ClassRegistry::init('Permission');
		$views = $this->getViewsSQL();
		foreach ($views as $view) {
			$permission->query($view);
		}
	}

	public static function getFunctionsSQL() {
		return array(
			"getGroupCategoryPermission" =>
				"DROP FUNCTION IF EXISTS getGroupCategoryPermission;
				CREATE FUNCTION `getGroupCategoryPermission`(`group_id` VARCHAR(36), `category_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
				    NO SQL
						BEGIN
						    DECLARE `permid` VARCHAR(36);
						    
								SELECT `gcp`.permission_id INTO `permid`
								FROM `groups_categories_permissions` gcp
								WHERE `gcp`.group_id = `group_id` 
								AND `gcp`.category_id = `category_id`;
								
						    RETURN `permid`;
						END;",

			"getGroupResourcePermission" =>
				"DROP FUNCTION IF EXISTS getGroupResourcePermission;
				CREATE FUNCTION `getGroupResourcePermission`(`group_id` VARCHAR(36), `resource_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
			    NO SQL
					BEGIN
					    DECLARE `permid` VARCHAR(36);

					    SELECT `grp`.permission_id INTO `permid`
							FROM `groups_resources_permissions` grp
							WHERE `grp`.group_id =  `group_id`
							AND `grp`.resource_id = `resource_id`;

					    RETURN `permid`;
					END;",

			"getUserCategoryPermission" =>
					"DROP FUNCTION IF EXISTS getUserCategoryPermission;
					CREATE FUNCTION `getUserCategoryPermission`(`user_id` VARCHAR(36), `category_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
					NO SQL
					BEGIN
				  DECLARE `permid` VARCHAR(36);
					
						SELECT `ucp`.permission_id INTO `permid`
						FROM `users_categories_permissions` ucp
						WHERE `ucp`.user_id =  `user_id`
						AND `ucp`.category_id = `category_id`;

				   RETURN `permid`;
				 	 END;",

				"getUserResourcePermission" =>
					"DROP FUNCTION IF EXISTS getUserResourcePermission;
					CREATE FUNCTION `getUserResourcePermission`(`user_id` VARCHAR(36), `resource_id` VARCHAR(36)) RETURNS varchar(36) CHARSET utf8
					NO SQL
					BEGIN
					  DECLARE `permid` VARCHAR(36);
						
SELECT `p`.id INTO `permid`
					  FROM permissions p 
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
					END;",

				"getPermissions" =>
					"DROP FUNCTION IF EXISTS getPermissions;
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
					END;"
		);
	}

	public function createFunctions() {
		$permission = ClassRegistry::init('Permission');
		$functions = $this->getFunctionsSQL();
		foreach ($functions as $f) {
			$permission->query($f);
		}
		// TODO : manage case where user is owner of the resource. What to do ? What should be the permission then ?
	}
}
