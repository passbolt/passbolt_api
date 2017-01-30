<?php
/**
 * Permission Schema
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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

			SELECT
				u.id AS user_id,
				r.id AS resource_id,
				dir_usr_rs_perm.id AS permission_id,
				dir_usr_rs_perm.type AS permission_type

			FROM resources r
			JOIN users u
				ON ( TRUE )

			LEFT JOIN permissions dir_usr_rs_perm
			ON (
				dir_usr_rs_perm.aro = 'User'
				AND dir_usr_rs_perm.aco = 'Resource'
				AND dir_usr_rs_perm.aco_foreign_key = r.id
				AND dir_usr_rs_perm.aro_foreign_key = u.id
			)

			WHERE dir_usr_rs_perm.id IS NOT NULL;"
		);
	}

}
