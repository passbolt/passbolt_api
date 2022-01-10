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
 * @since         3.0.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;
use Cake\Validation\Validation;

class V300AddResourceTypesToResources extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $id = $this->getAdapter()->quoteColumnName('id');
        $resourceTypeId = $this->getAdapter()->quoteColumnName('resource_type_id');
        $slug = $this->getAdapter()->quoteColumnName('slug');
        $resourceTypes = $this->getAdapter()->quoteTableName('resource_types');
        $resources = $this->getAdapter()->quoteTableName('resources');

        $defaultType = $this->fetchAll("SELECT $id FROM $resourceTypes WHERE $slug='password-string'");

        if (empty($defaultType) || !Validation::uuid($defaultType[0]['id'])) {
            return;
        }

        $this->execute("UPDATE $resources SET $resourceTypeId='{$defaultType[0]['id']}' WHERE $resourceTypeId IS NULL");
    }
}
// @codingStandardsIgnoreEnd
