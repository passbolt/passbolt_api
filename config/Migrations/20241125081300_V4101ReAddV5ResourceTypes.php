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
use Cake\I18n\FrozenTime;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V4101ReAddV5ResourceTypes extends AbstractMigration
{
    /**
     * Up Method.
     *
     * @link https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $data = [];
        $resourceType = $this->fetchRow("SELECT slug from resource_types where slug='v5-password-string'");
        if (!isset($resourceType)) {
            $data[] = [
                'id' => UuidFactory::uuid('resource-types.id.v5-password-string'),
                'slug' => ResourceType::SLUG_V5_PASSWORD_STRING,
                'name' => 'Simple Password (Deprecated)',
                'description' => 'The original passbolt resource type, kept for backward compatibility reasons.',
                'definition' => json_encode([]),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ];
        }

        $resourceType = $this->fetchRow("SELECT slug from resource_types where slug='v5-default'");
        if (!isset($resourceType)) {
            $data[] = [
                'id' => UuidFactory::uuid('resource-types.id.v5-default'),
                'slug' => ResourceType::SLUG_V5_DEFAULT,
                'name' => 'Default resource type',
                'description' => 'The new default resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ];
        }

        $resourceType = $this->fetchRow("SELECT slug from resource_types where slug='v5-totp-standalone'");
        if (!isset($resourceType)) {
            $data[] = [
                'id' => UuidFactory::uuid('resource-types.id.v5-totp-standalone'),
                'slug' => ResourceType::SLUG_V5_TOTP_STANDALONE,
                'name' => 'Standalone TOTP',
                'description' => 'The new standalone TOTP resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ];
        }

        $resourceType = $this->fetchRow("SELECT slug from resource_types where slug='v5-default-with-totp'");
        if (!isset($resourceType)) {
            $data[] = [
                'id' => UuidFactory::uuid('resource-types.id.v5-default-with-totp'),
                'slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP,
                'name' => 'Default resource type with TOTP',
                'description' => 'The new default resource type with a TOTP introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new FrozenTime())->toDateTimeString(),
                'modified' => (new FrozenTime())->toDateTimeString(),
            ];
        }

        if (!empty($data)) {
            $resourceTypesTable = $this->table('resource_types');
            $resourceTypesTable->insert($data)->saveData();
        }
    }
}
// @codingStandardsIgnoreEnd
