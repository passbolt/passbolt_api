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
 * @since         5.4.0
 */

use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V540UpdateV5ResourceTypesDefinitions extends AbstractMigration
{
    /**
     * Up Method.
     *
     * @link https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        // Update v5 password string definition.
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5PasswordString())
            ->where(['slug' => ResourceType::SLUG_V5_PASSWORD_STRING])
            ->execute();
        // Update v5 default definition.
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5Default())
            ->where(['slug' => ResourceType::SLUG_V5_DEFAULT])
            ->execute();
        // Update v5 default totp definition.
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5DefaultTotp())
            ->where(['slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP])
            ->execute();
        // Update v5 standalone totp definition.
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5Totp())
            ->where(['slug' => ResourceType::SLUG_V5_TOTP_STANDALONE])
            ->execute();
        // Update v5 standalone custom fields definition.
        $this->getUpdateBuilder()
            ->update('resource_types')
            ->set('definition', SlugDefinition::v5CustomFields())
            ->where(['slug' => ResourceType::SLUG_V5_CUSTOM_FIELD_STANDALONE])
            ->execute();
    }
}
