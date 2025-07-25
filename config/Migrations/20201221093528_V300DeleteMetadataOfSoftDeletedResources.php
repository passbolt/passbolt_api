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

use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;

class V300DeleteMetadataOfSoftDeletedResources extends AbstractMigration
{
    /**
     * Clear metadata of soft deleted resources
     * @return void
     */
    public function up()
    {
        $this
            ->getUpdateBuilder()
            ->update('resources')
            ->set([
                'username' => null,
                'uri' => null,
                'description' => null,
            ])
            ->where(['deleted' => true]);
    }
}
