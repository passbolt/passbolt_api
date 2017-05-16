<?php
/**
 * User Role Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
/**
 * @SWG\Definition(
 * @SWG\Xml(name="Role"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="UUID of the role, the primary identifier",
 *     example="e3ed5a94-8ef0-35a5-a01f-8e62539b758b"
 *   ),
 * @SWG\Property(
 *     property="name",
 *     type="string",
 *     description="Role name",
 *     example="user"
 *   ),
 * @SWG\Property(
 *     property="description",
 *     type="string",
 *     description="Role description",
 *     example="Logged in user"
 *   ),
 * )
 *
 * Note: modified, created, created_by, modified_by properties are also present
 * you will find them in the database schema but they are not in use by the API at the moment
 * so we are omitting them here.
 */
class Role extends AppModel {
	const GUEST = 'guest';
	const USER = 'user';
	const ADMIN = 'admin';
	const ROOT = 'root';

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null, $data = null) {
		switch ($case) {
			case 'view':
				return [
					'fields' => [
						'Role.id',
						'Role.name',
						'Role.description'
					]
				];
			default:
				return [
					'fields' => []
				];
		}
	}
}
