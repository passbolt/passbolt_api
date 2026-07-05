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

use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

/**
 * Re-writes the stored v5-default and v5-default-with-totp definitions so existing installs gain the
 * `passkeys` secret array (the attach model). Fresh installs already get it from SlugDefinition;
 * without this migration, upgraded installs would keep a definition that diverges from code and from
 * fresh installs, so any consumer that trusts the API `/resource-types` response would reject the
 * passkeys array. Mirrors V540/V550 UpdateV5ResourceTypesDefinitions.
 */
class V5130UpdateV5ResourceTypesDefinitionsForPasskeys extends AbstractMigration
{
    /**
     * Up Method.
     *
     * @link https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        // Update v5 default definition (now carries the `passkeys` secret array).
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5Default())
            ->where(['slug' => ResourceType::SLUG_V5_DEFAULT])
            ->execute();
        // Update v5 default totp definition (now carries the `passkeys` secret array).
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5DefaultTotp())
            ->where(['slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP])
            ->execute();
    }
}
