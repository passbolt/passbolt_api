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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPoliciesUpdate\Test\Factory;

use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Faker\Generator;
use Passbolt\PasswordPolicies\Model\Dto\PassphraseGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPoliciesUpdate\Model\Table\PasswordPoliciesSettingsTable;

/**
 * @method \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting|\Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting[] persist()
 * @method \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting getEntity()
 * @method \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting[] getEntities()
 * @method static \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting get($primaryKey, array $options = [])
 * @see \Passbolt\PasswordPoliciesUpdate\Model\Table\PasswordPoliciesSettingsTable
 */
class PasswordPoliciesSettingFactory extends OrganizationSettingFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return PasswordPoliciesSettingsTable::class;
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            /** @var \Passbolt\PasswordPoliciesUpdate\Model\Table\PasswordPoliciesSettingsTable $registry */
            $registry = TableRegistry::getTableLocator()->get($this->getRootTableRegistryName());

            return [
                'id' => $faker->uuid(),
                'property' => $registry->getProperty(),
                'property_id' => $registry->getPropertyId(),
                'value' => [
                    'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
                    'external_dictionary_check' => true,
                    'password_generator_settings' => PasswordGeneratorSettingsDto::createFromDefault()->toArray(),
                    'passphrase_generator_settings' => PassphraseGeneratorSettingsDto::createFromDefault()->toArray(),
                    'source' => 'default',
                ],
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }
}
