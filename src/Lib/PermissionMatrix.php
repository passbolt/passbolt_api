<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace PassboltTestData\Lib;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;

class PermissionMatrix
{

    private static $mapPermissionTypes = [
        '' => 0,
        'R' => Permission::READ,
        'U' => Permission::UPDATE,
        'O' => Permission::OWNER,
    ];

    /**
     * Retrieve the expected access for all the resources and for all the users defined in
     * the PassboltTestData.Base scenario.
     *
     * The access can be found in the csv file \plugins\PassboltTestData\data\calculated_users_resources_permissions.csv
     * If you modify the access, report the changes in the :
     * - Google doc "Permissions Matrix" (https://docs.google.com/a/passbolt.com/spreadsheets/d/1Yd5orUPZMKnzy-f4X8Nz5NXnX74fjFGJHAthVKCgoWc/edit?usp=sharing)
     * - Modify the permissions data task \plugins\PassboltTestData\src\Shell\Task\Base\PermissionsDataTask
     *
     * The result can be oriented by resources or by users.
     * When oriented by resources, the result is formatted as following :
     * [
     *   ResourceAlias => [
     *     UserAlias => PermissionType,
     *     ...
     *   ]
     * ]
     *
     * If oriented by users :
     * [
     *   UserAlias => [
     *     ResourceAlias => PermissionType,
     *     ...
     *   ]
     * ]
     *
     * The aliases can be used to retrieve the resources and users uuids
     * > UuidFactory::uuid("resource.id.$resourceAlias")
     * > UuidFactory::uuid("user.id.$userAlias"')
     *
     * Return example:
     * [
     *   apache => [
     *     ada => 15, betty => 7, carol => 1, dame => 1, edith => 0 ...
     *   ],
     *   bower => [
     *     ada => 1, betty => 0, carol => 15, dame => 7, edith => 1 ...
     *   ],
     *   ...
     * ]
     *
     * @param string $orientation Return the permissions sorted by
     * @return array
     */
    public static function getCalculatedUsersResourcesPermissions($orientation = 'resource')
    {
        $csvPath = __DIR__ . '/../../data/calculated_users_resources_permissions.csv';
        $return = self::_loadCsv($csvPath, $orientation);

        return $return;
    }

    /**
     * Get the direct access defined for all the users and all the resources.
     *
     * @param string $orientation resource|user
     * @return array
     */
    public static function getUsersResourcesPermissions($orientation = 'resource')
    {
        $csvPath = __DIR__ . '/../../data/users_resources_permissions.csv';

        return self::_loadCsv($csvPath, $orientation);
    }

    /**
     * Get the direct users access for a given resource.
     * @param string $resourceId uuid
     * @return array|null
     */
    public static function getUsersResourcePermissions($resourceId)
    {
        $matrix = self::getUsersResourcesPermissions();
        $resourceAlias = array_reduce(array_keys($matrix), function ($carry, $item) use ($resourceId) {
            if ($resourceId == UuidFactory::uuid("resource.id.$item")) {
                $carry = $item;
            }

            return $carry;
        }, null);

        if (isset($matrix[$resourceAlias])) {
            return $matrix[$resourceAlias];
        }

        return null;
    }

    /**
     * Get the direct access defined for all the groups and all the resources.
     *
     * @param string $orientation resource|user
     * @return array
     */
    public static function getGroupsResourcesPermissions($orientation = 'resource')
    {
        $csvPath = __DIR__ . '/../../data/groups_resources_permissions.csv';

        return self::_loadCsv($csvPath, $orientation);
    }

    /**
     * Get the direct groups access for a given resource
     *
     * @param string $resourceId uuod
     * @return array|null
     */
    public static function getGroupsResourcePermissions($resourceId)
    {
        $matrix = self::getGroupsResourcesPermissions();
        $resourceAlias = array_reduce(array_keys($matrix), function ($carry, $item) use ($resourceId) {
            if ($resourceId == UuidFactory::uuid("resource.id.$item")) {
                $carry = $item;
            }

            return $carry;
        }, null);

        if (isset($matrix[$resourceAlias])) {
            return $matrix[$resourceAlias];
        }

        return null;
    }

    /**
     * Load a csv file
     *
     * @param string $file name
     * @param string $orientation resource|user
     * @return array
     */
    private static function _loadCsv($file, $orientation = 'resource')
    {
        $matrix = [];
        foreach (file($file) as $chunks) {
            $csv[] = str_getcsv($chunks, separator: ',', enclosure: '"', escape: '');
        }

        // Extract the csv header
        $header = array_shift($csv);
        $header = array_slice($header, 1);

        // Build the matrix.
        foreach ($csv as $key => $value) {
            $row = array_slice($value, 1);
            $row = array_map(function ($p) {
                return self::$mapPermissionTypes[$p];
            }, $row);

            if ($orientation == 'resource') {
                $matrix[$value[0]] = array_combine($header, $row);
            } elseif ($orientation == 'user' || $orientation == 'group') {
                foreach ($header as $i => $column) {
                    $matrix[$column][$value[0]] = $row[$i];
                }
            }
        }

        return $matrix;
    }
}
