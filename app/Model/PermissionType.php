<?php
/**
 * PermissionType Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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

	const DENY = '0';
	const READ = '1';
	const CREATE = '3';
	const UPDATE = '7';
	const OWNER = '15';

/**
 * Check if the given type is a valid permission type
 *
 * @param string $type The type to check
 * @return bool
 */
	public function isValidSerial($type) {
		switch ($type) {
			case self::DENY:
			case self::READ:
			case self::CREATE:
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
			'DENY' => self::DENY,
			'READ' => self::READ,
			'CREATE' => self::CREATE,
			'UPDATE' => self::UPDATE,
			'ADMIN' => self::OWNER,
		];
	}
}
