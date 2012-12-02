<?php
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
		$this->createPermissionFunctions();
		$this->createPermissionCacheView();
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
}
?>
