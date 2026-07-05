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
 * @since         5.13.0
 */

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V5130AddPasskeyResourceType extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $data = [
            [
                'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSKEY),
                'slug' => ResourceType::SLUG_V5_PASSKEY,
                'name' => 'Passkey',
                'description' => 'A passkey (WebAuthn credential) stored on behalf of a third-party website.',
                'definition' => SlugDefinition::v5Passkey(),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
        ];

        $resourceTypesTable = $this->table('resource_types');
        $resourceTypesTable->insert($data)->saveData();
    }
}
