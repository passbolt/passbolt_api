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
 * @since         5.3.0
 */

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V530AddCustomFieldStandaloneResourceType extends AbstractMigration
{
    /**
     * Up Method.
     *
     * @link https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $data = [
            [
                'id' => UuidFactory::uuid('resource-types.id.v5-custom-fields'),
                'slug' => ResourceType::SLUG_V5_CUSTOM_FIELD_STANDALONE,
                'name' => 'Standalone custom fields',
                'description' => 'A resource with standalone custom fields.',
                'definition' => \Passbolt\ResourceTypes\Model\Definition\SlugDefinition::v5CustomFields(),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ]
        ];

        $resourceTypesTable = $this->table('resource_types');
        $resourceTypesTable->insert($data)->saveData();
    }
}
