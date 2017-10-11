<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace PassboltTestData\Lib;

use App\Model\Entity\Permission;

class PermissionMatrix {

    private static $mapPermissionTypes = [
        '' => 0,
        'R' => Permission::READ,
        'U' => Permission::UPDATE,
        'O' => Permission::OWNER,
    ];

    public static function getCalculatedUsersResourcesPermissions($orientation = 'resource') {
        $csvPath = __DIR__ . '/../../data/calculated_users_resources_permissions.csv';
        return self::loadCsv($csvPath, $orientation);
    }

    public static function getUsersResourcesPermissions($orientation = 'resource') {
        $csvPath = __DIR__ . '/../../data/users_resources_permissions.csv';
        return self::loadCsv($csvPath, $orientation);
    }

    public static function getGroupsResourcesPermissions() {
        $csvPath = __DIR__ . '/../../data/groups_resources_permissions.csv';
        return self::loadCsv($csvPath, 'resource');
    }

    private static function loadCsv($file, $orientation = 'resource')
	{
		$matrix = array();
		$csv = array_map('str_getcsv', file($file));

		// Extract the csv header
		$header = array_shift($csv);
		$header = array_slice($header, 1);

		// Build the matrix.
        foreach ($csv as $key => $value) {
            $row = array_slice($value, 1);
            $row = array_map(function($p) {
                return self::$mapPermissionTypes[$p];
            }, $row);

            if ($orientation == 'resource') {
                $matrix[$value[0]] = array_combine($header, $row);
            } else if ($orientation == 'user') {
                foreach ($header as $i => $column) {
                    $matrix[$column][$value[0]] = $row[$i];
                }
            }
        }

		return $matrix;
	}
}
