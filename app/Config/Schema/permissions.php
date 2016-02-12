<?php
/**
 * Permission Schema
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class PermissionsSchema {

	public function init() {
		// Create the permissions functions
		$this->createFunctions();
		// Create the permissions views
		$this->createViews();
	}

	public static function getViewsSQL() {
		return array(
			/*
			For each categories
				Select all its parents (On a left/right tree a parent has a left <=, and right >=)
			*/
			"categories_parents" => "
			CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW categories_parents AS
			SELECT
				c.id AS child_id,                     /* Category id */
				c.lft AS child_lft,                   /* Category left */
				c.rght AS child_rght,                 /* Category right */
				cp.id AS id,                          /* Parent category id */
				cp.lft AS lft,                        /* Parent Category left */
				cp.rght AS rght                       /* Parent Category right */
			FROM categories c, categories cp
			WHERE cp.lft <= c.lft
				AND cp.rght >= c.rght;
			",

			/*
			 For each direct permission defined with ARO=Group & ACO=Category
			    Foreach category children
			        Aplpy the same permission

			 Note : A GROUP Permission cannot be applied to a Category, if a Permission
			 has already been defined on a parent.
			 */
			"groups_categories_permissions" => "
			CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW groups_categories_permissions AS
			SELECT
				`g`.id AS group_id,
				`cp`.child_id AS category_id,
				`p`.id AS permission_id,
				`p`.type AS permission_type
			FROM permissions p
			LEFT JOIN `categories_parents` cp
				ON `cp`.id=`p`.aco_foreign_key
			INNER JOIN `groups` g
				ON `g`.id=`p`.aro_foreign_key
			WHERE `p`.aro = 'Group'
				AND `p`.aco = 'Category'
			ORDER BY `g`.id, `cp`.child_id, `cp`.lft DESC;
			",

			/*
			 For each resources
				For each groups
					If a direct permission has been defined for the resource and the group
						return that permission
				    Else If a permission has been defined on a parent category for the group
						return the higher permission
			 */
			"groups_resources_permissions" => "
			CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW groups_resources_permissions AS

			SELECT
				`g`.id AS group_id,                     /* Group */
				`r`.id AS resource_id,                  /* Resource */
				`dir_grp_cat_perm`.id AS dir_perm_id,        /* Direct permission id */
				`inh_grp_cat_perm`.id AS inh_grp_perm_id,    /* Inherited Group Cat Perm : a group has a direct permissions on a parent category */

				/* The result permission is following the priority :
				1. direct User Perm;
				2. Inherited Group Perm;
				*/
				IFNULL(`dir_grp_cat_perm`.id, `inh_grp_cat_perm`.id) AS permission_id,
				IFNULL(`dir_grp_cat_perm`.type, `inh_grp_cat_perm`.type) AS permission_type

			FROM (`resources` r JOIN `groups` g)

			/* 1. direct User Perm */
			LEFT JOIN `permissions` dir_grp_cat_perm
			ON (
				`dir_grp_cat_perm`.aro = 'Group'
				AND `dir_grp_cat_perm`.aco = 'Resource'
				AND `dir_grp_cat_perm`.aco_foreign_key = `r`.id
				AND `dir_grp_cat_perm`.aro_foreign_key = `g`.id
			)

			/* Inherited Group Perm */
			LEFT JOIN `permissions` inh_grp_cat_perm
			ON (
				inh_grp_cat_perm.id = (
					SELECT `gcp`.permission_id
					FROM `groups_categories_permissions` gcp,
						`categories_resources` cr
					WHERE `cr`.resource_id = `r`.id
						AND `gcp`.group_id = `g`.id
						AND `gcp`.category_id = `cr`.category_id
					ORDER BY `gcp`.permission_type DESC
					LIMIT 1
				)
			);
			",

			/*
			 For each categories
				For each users
					If a direct permission has been defined for the category and the user
						return that permission
				    Else If a direct permission has been defined for the user on a parent category
						return that permission
					Else If a permission has been defined for one of the user's groups
						return the higher permission
					Else
						return null
			 */
			"users_categories_permissions" => "
			CREATE OR REPLACE ALGORITHM=UNDEFINED VIEW users_categories_permissions AS
			SELECT
				`u`.id AS user_id,                      /* User */
				`c`.id AS category_id,                  /* Category */
				`dir_usr_cat_perm`.id AS dir_perm_id,   /* Direct permission id */
				`inh_usr_cat_perm`.id AS inh_usr_perm_id,   /* Inherited User Cat Perm : the user has a direct permission on a parent category */
				`inh_grp_cat_perm`.id AS inh_grp_perm_id,   /* Inherited Group Cat Perm : a group has a direct permissions on a parent category */

				/* The result permission is following the priority :
				1. direct User Cat Perm;
				2. Inherited User Cat Perm;
				3. Inherited Group Cat Perm;
				*/
				IFNULL(`dir_usr_cat_perm`.id, IFNULL(`inh_usr_cat_perm`.id, `inh_grp_cat_perm`.id)) AS permission_id,
				IFNULL(`dir_usr_cat_perm`.type, IFNULL(`inh_usr_cat_perm`.type, `inh_grp_cat_perm`.type)) AS permission_type

			FROM (`categories` c JOIN `users` u)

			/* 1. direct User Cat Perm;  */
			LEFT JOIN `permissions` dir_usr_cat_perm
			ON (
				`dir_usr_cat_perm`.aro = 'User'
				AND `dir_usr_cat_perm`.aco='Category'
				AND `dir_usr_cat_perm`.aco_foreign_key = `c`.id
				AND `dir_usr_cat_perm`.aro_foreign_key = `u`.id
			)

			/* 2. Inherited User Cat Perm */
			LEFT JOIN `permissions` inh_usr_cat_perm
			ON (
				inh_usr_cat_perm.id = (
					SELECT `pu_pc`.id
					FROM `permissions` pu_pc, /* user's permissions applied to parent categories */
						`categories_parents` cp
					WHERE `pu_pc`.aro = 'User'
						AND `pu_pc`.aro_foreign_key = `u`.id
						AND `pu_pc`.aco_foreign_key = `cp`.id
						AND `cp`.child_id = `c`.id
					ORDER BY `cp`.lft DESC
					LIMIT 1
				)
			)

			/* 3. Inherited Group Cat Perm */
			LEFT JOIN `permissions` inh_grp_cat_perm
			ON (
				inh_grp_cat_perm.id = (
					SELECT `gcp`.permission_id
					FROM `groups_categories_permissions` gcp,
						`groups_users` gu
					WHERE `gcp`.category_id = `c`.id
						AND `gu`.user_id = `u`.id
						AND `gu`.group_id = `gcp`.group_id
					ORDER BY `gcp`.permission_type DESC
					LIMIT 1
				)
			);
			",

			/*
			 For each resources
				For each users
					If a direct permission has been defined for the resource and the user
						return that permission
				    Else If the user has a direct permission on one of the parent categories of the resource
				≤		return that permission
					Else If a user's group has a permission (direct or inherited) on the resource
						return the higher
					Else return null
			 */
			"users_resources_permissions" => "
			CREATE OR REPLACE ALGORITHM=MERGE VIEW users_resources_permissions AS

			SELECT
				`u`.id AS user_id,                      /* User */
				`r`.id AS resource_id,                  /* Resource */

				/* The result permission is following the priority :
				1. direct User Rs Perm;
				2. Inherited User Cat Perm;
				3. Inherited Group Rs Perm;
				4. Inherited Group Cat Perm;
				*/
				IFNULL(`dir_usr_rs_perm`.id, IFNULL(`inh_usr_cat_perm`.id, `inh_grp_cat_perm`.id)) AS permission_id,
				IFNULL(`dir_usr_rs_perm`.type, IFNULL(`inh_usr_cat_perm`.type, `inh_grp_cat_perm`.type)) AS permission_type

			FROM (`resources` r JOIN `users` u)

			/* 1. direct User Rs Perm  */
			LEFT JOIN `permissions` dir_usr_rs_perm
			ON (
				`dir_usr_rs_perm`.aro = 'User'
				AND `dir_usr_rs_perm`.aco = 'Resource'
				AND `dir_usr_rs_perm`.aco_foreign_key = `r`.id
				AND `dir_usr_rs_perm`.aro_foreign_key = `u`.id
			)

			/* 2. Inherited User Cat Perm */
			LEFT JOIN `permissions` inh_usr_cat_perm
			ON (
				inh_usr_cat_perm.id = (
					SELECT IFNULL(`ucp`.dir_perm_id, `ucp`.inh_usr_perm_id) AS id
					FROM `users_categories_permissions` ucp,
						`categories_resources` cr
					WHERE `cr`.resource_id = `r`.id
						AND `ucp`.category_id = `cr`.category_id
						AND `ucp`.user_id = `u`.id
					ORDER BY `ucp`.permission_type DESC
					LIMIT 1
				)
			)

			/* 3. Inherited Group Rs Perm
			4. Inherited Group Cat Perm  */
			LEFT JOIN `permissions` inh_grp_cat_perm ON (
				inh_grp_cat_perm.id = (
					SELECT `grp`.permission_id
					FROM `groups_resources_permissions` grp,
						`groups_users` gu
					WHERE `grp`.resource_id = `r`.id
						AND `gu`.user_id = `u`.id
						AND `gu`.group_id = `grp`.group_id
					ORDER BY `grp`.permission_type DESC
					LIMIT 1
				)
			);"
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
							
							SELECT `urp`.permission_id INTO `permid`
							FROM `users_resources_permissions` urp
							WHERE `urp`.user_id =  `user_id`
							AND `urp`.resource_id = `resource_id`;

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
