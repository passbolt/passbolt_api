<?php
/**
 * PermissionType Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class PermissionType extends AppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = "permissions_types";

/**
 * The name of the primary key field for this model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
 */
	public $primaryKey = 'serial';

/**
 * Passbolt permission types.
 */
	const DENY = '0'; // DENY is not an allowed permission type, but it's useful to keep it for testing purpose.
	const READ = '1';
	const UPDATE = '7';
	const OWNER = '15';

/**
 * Check if the given type is a valid permission type
 *
 * @param string $type The type to check
 * @return bool
 */
	public static function isValidSerial($type) {
		switch ($type) {
			case self::READ:
			case self::UPDATE:
			case self::OWNER:
				return true;
			default:
				return false;
		}
	}

/**
 * Return all the permission types
 *
 * @return array permissionTypes
 */
	public static function getAll() {
		return [
			'READ' => self::READ,
			'UPDATE' => self::UPDATE,
			'ADMIN' => self::OWNER,
		];
	}
}
