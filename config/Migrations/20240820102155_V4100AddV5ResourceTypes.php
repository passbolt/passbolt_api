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
 * @since         4.10.0
 */
// @codingStandardsIgnoreStart
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V4100AddV5ResourceTypes extends AbstractMigration
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
                'id' => UuidFactory::uuid('resource-types.id.v5-password-string'),
                'slug' => ResourceType::SLUG_V5_PASSWORD_STRING,
                'name' => 'Simple Password (Deprecated)',
                'description' => 'The original passbolt resource type, kept for backward compatibility reasons.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.v5-default'),
                'slug' => ResourceType::SLUG_V5_DEFAULT,
                'name' => 'Default resource type',
                'description' => 'The new default resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.v5-totp-standalone'),
                'slug' => ResourceType::SLUG_V5_TOTP_STANDALONE,
                'name' => 'Standalone TOTP',
                'description' => 'The new standalone TOTP resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            [
                'id' => UuidFactory::uuid('resource-types.id.v5-default-with-totp'),
                'slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP,
                'name' => 'Default resource type with TOTP',
                'description' => 'The new default resource type with a TOTP introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
        ];

        $resourceTypesTable = $this->table('resource_types');
        $resourceTypesTable->insert($data)->saveData();
    }
}
// @codingStandardsIgnoreEnd
