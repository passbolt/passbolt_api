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
use Cake\I18n\DateTime;
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
        $data = [
            'v5-password-string' => [
                'id' => UuidFactory::uuid('resource-types.id.v5-password-string'),
                'slug' => ResourceType::SLUG_V5_PASSWORD_STRING,
                'name' => 'Simple Password (Deprecated)',
                'description' => 'The original passbolt resource type, kept for backward compatibility reasons.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            'v5-default' => [
                'id' => UuidFactory::uuid('resource-types.id.v5-default'),
                'slug' => ResourceType::SLUG_V5_DEFAULT,
                'name' => 'Default resource type',
                'description' => 'The new default resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            'v5-totp-standalone' => [
                'id' => UuidFactory::uuid('resource-types.id.v5-totp-standalone'),
                'slug' => ResourceType::SLUG_V5_TOTP_STANDALONE,
                'name' => 'Standalone TOTP',
                'description' => 'The new standalone TOTP resource type introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
            'v5-default-with-totp' => [
                'id' => UuidFactory::uuid('resource-types.id.v5-default-with-totp'),
                'slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP,
                'name' => 'Default resource type with TOTP',
                'description' => 'The new default resource type with a TOTP introduced with v5.',
                'definition' => json_encode([]),
                'created' => (new DateTime())->toDateTimeString(),
                'modified' => (new DateTime())->toDateTimeString(),
            ],
        ];

        $stmt = $this->query("SELECT slug FROM resource_types WHERE slug IN ('v5-password-string', 'v5-default', 'v5-totp-standalone', 'v5-default-with-totp')");
        $rows = $stmt->fetchAll(\PDO::FETCH_BOTH);
        foreach ($rows as $row) {
            // Do not insert if already present
            if ($row['slug'] === 'v5-password-string') {
                unset($data['v5-password-string']);
            } elseif ($row['slug'] === 'v5-default') {
                unset($data['v5-default']);
            } elseif ($row['slug'] === 'v5-totp-standalone') {
                unset($data['v5-totp-standalone']);
            } elseif ($row['slug'] === 'v5-default-with-totp') {
                unset($data['v5-default-with-totp']);
            }
        }

        if (!empty($data)) {
            $resourceTypesTable = $this->table('resource_types');
            $resourceTypesTable->insert($data)->saveData();
        }
    }
}
// @codingStandardsIgnoreEnd
