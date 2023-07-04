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
 * @since         4.1.0
 */
// @codingStandardsIgnoreStart

use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Migrations\AbstractMigration;
use Passbolt\ResourceTypes\Model\Definition\SlugDefinition;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class V410RemoveTypeFromTotpResourceTypes extends AbstractMigration
{
    use LocatorAwareTrait;

    /**
     * @link https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     *
     * @return void
     */
    public function up(): void
    {
        $colSlug = $this->getAdapter()->quoteColumnName('slug');
        $colDefinition = $this->getAdapter()->quoteColumnName('definition');
        $colModified = $this->getAdapter()->quoteColumnName('modified');
        $tableResourceTypes = $this->getAdapter()->quoteTableName('resource_types');

        $modified = FrozenTime::now()->format('Y-m-d H:i:s');
        $totpResourcesTypes = [
            ResourceType::SLUG_STANDALONE_TOTP => SlugDefinition::totp(),
            ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP => SlugDefinition::passwordDescriptionTotp(),
        ];
        foreach ($totpResourcesTypes as $slug => $definition) {
            $this->execute(
                "UPDATE {$tableResourceTypes} SET {$colDefinition}='{$definition}', {$colModified}='{$modified}' WHERE {$colSlug}='{$slug}'"
            );
        }
    }
}
// @codingStandardsIgnoreEnd
