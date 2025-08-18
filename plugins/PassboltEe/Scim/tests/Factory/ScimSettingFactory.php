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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Test\Factory;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Passbolt\Scim\Model\Table\ScimSettingsTable;

/**
 * OrganizationSettingFactory
 *
 * @method \Passbolt\Scim\Model\Entity\ScimSetting|\Passbolt\Scim\Model\Entity\ScimSetting[] persist()
 * @method \Passbolt\Scim\Model\Entity\ScimSetting getEntity()
 * @method \Passbolt\Scim\Model\Entity\ScimSetting[] getEntities()
 * @method static \Passbolt\Scim\Model\Entity\ScimSetting get($primaryKey, array $options = [])
 * @method static \Passbolt\Scim\Model\Entity\ScimSetting firstOrFail(ExpressionInterface|\Closure|array|string|null $conditions = null)
 */
class ScimSettingFactory extends OrganizationSettingFactory
{
    use OpenPGPCommonServerOperationsTrait;

    public const SCIM_TEST_SETTING_ID = '818b3361-e1a5-40cd-b423-775f1bd35c17';
    public const SCIM_TEST_SECRET_TOKEN = 'pb_TEST_TOKEN_FOR_SCIM_INTEGRATION_IN_PASSBOLT';

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return ScimSettingsTable::class;
    }

    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $registry */
        $registry = TableRegistry::getTableLocator()->get($this->getRootTableRegistryName());
        $this->patchData([
            'property' => $registry->getProperty(),
            'property_id' => $registry->getPropertyId(),
        ]);
    }

    /**
     * @return $this
     */
    public function default()
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);

        return $this->setField('value', $gpg->encrypt(json_encode($this->getDefaultValue())));
    }

    public function getDefaultValue(): array
    {
        return [
            'setting_id' => self::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => Security::hash(self::SCIM_TEST_SECRET_TOKEN, 'sha256'),
        ];
    }
}
