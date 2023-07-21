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
 * @since         4.0.0
 */
// @codingStandardsIgnoreStart
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V400AddTotpResourceTypes extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $resourceTypesTable = $this->table('resource_types');
        $data = [
            [
                /**
                 * Standalone TOTP
                 */
                'id' => UuidFactory::uuid('resource-types.id.totp'),
                'slug' => ResourceType::SLUG_STANDALONE_TOTP,
                'name' => 'Standalone TOTP',
                'description' => 'A resource with standalone TOTP fields.',
                'definition' => SlugDefinition::totp(),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ],
            [
                /**
                 * TOTP with password & description
                 */
                'id' => UuidFactory::uuid('resource-types.id.password-description-totp'),
                'slug' => ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP,
                'name' => 'Password, Description and TOTP',
                'description' => 'A resource with encrypted password, description and TOTP fields.',
                'definition' => SlugDefinition::passwordDescriptionTotp(),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ],
        ];

        $resourceTypesTable->insert($data)->saveData();
    }
}
// @codingStandardsIgnoreEnd
