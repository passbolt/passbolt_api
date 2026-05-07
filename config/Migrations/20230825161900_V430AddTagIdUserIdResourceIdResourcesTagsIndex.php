<?php
declare(strict_types=1);

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
 * @since         4.3.0
 */

use Migrations\AbstractMigration;

class V430AddTagIdUserIdResourceIdResourcesTagsIndex extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     *
     * @return void
     */
    public function up(): void
    {
        /*
         * Improves the performances of resources tags related queries:
         * - Retrieving tags associated to resources a user has access, used when performing a query contain['Tags']
         *   on resources.json.
         *   It requires the index to contain the columns tag_id, user_id and resource_id
         * - Updating a personal user tag is updated.
         *   It requires the index to contain the tag_id and user_id.
         *   It also required the index to contain tag_id alone for a separated request.
         * - Retrieving tags a user has access, used when performing a tags.json.
         *   It requires the index to contain the columns tag_id, user_id and resource_id.
         */
        $this->table('resources_tags')->addIndex([
            'tag_id',
            'user_id',
            'resource_id',
        ])->save();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     *
     * @return void
     */
    public function down()
    {
    }
}
