<?php
/**
 * User Role Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Role extends AppModel {
	const GUEST = 'guest';
	const USER = 'user';
	const ADMIN = 'admin';
	const ROOT = 'root';

	public static function getFindFields($case = '', $role = Role::USER) {
		$returnValue = [];
		switch ($case) {
			case 'view':
				$returnValue = [
					'fields' => [
						'Role.id',
						'Role.name'
					]
				];
				break;
			default:
				$returnValue = [
					'fields' => []
				];
				break;
		}

		return $returnValue;
	}
}
