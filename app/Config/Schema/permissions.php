<?php
/**
 * Permission Schema
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class PermissionsSchema {

/**
 * Init
 *
 * @return void
 */
	public function init() {
		$permission = ClassRegistry::init('Permission');
		$views = $this->getViewsSQL();
		foreach ($views as $view) {
			$permission->query($view);
		}
	}

/**
 * Get SQL View
 *
 * @return array
 */
	public static function getViewsSQL() {
		return array(
			"users_resources_permissions" => "
			CREATE OR REPLACE VIEW users_resources_permissions AS

				# Pre-calculate all the permission a user could have for all the resources stored in passbolt.
				SELECT
					users.id AS user_id,
					resources.id AS resource_id,

					# Return the highest permission between :
					#  - A permission given directly to a user for a target resource:
					#  - A permission given to a group the user belongs to.

					IF(COALESCE(direct_user_resource_permission.type, 0) > COALESCE(inherited_groups_resources_permission.type, 0),
						direct_user_resource_permission.id,
						inherited_groups_resources_permission.id) AS permission_id,

					IF(COALESCE(direct_user_resource_permission.type, 0) > COALESCE(inherited_groups_resources_permission.type, 0),
						direct_user_resource_permission.type,
						inherited_groups_resources_permission.type) AS permission_type

				FROM
					users
					JOIN resources
						ON (TRUE)

				# Find the user direct permission (if any).
				LEFT JOIN permissions direct_user_resource_permission
				ON (
					direct_user_resource_permission.aco_foreign_key = resources.id
					AND direct_user_resource_permission.aro_foreign_key = users.id
				)

				# Find the highest permission among the groups the user belongs to (if any).
				LEFT JOIN permissions inherited_groups_resources_permission
				ON (
					inherited_groups_resources_permission.id = (
						SELECT groups_permissions.id
						FROM
							permissions groups_permissions,
							groups_users
						WHERE
							groups_users.user_id = users.id
							AND groups_permissions.aro_foreign_key = groups_users.group_id
							AND groups_permissions.aco_foreign_key = resources.id
						ORDER BY groups_permissions.type DESC
						LIMIT 1
					)
				)

				WHERE direct_user_resource_permission.id IS NOT NULL
					OR inherited_groups_resources_permission.id IS NOT NULL;
			"
		);
	}

}
