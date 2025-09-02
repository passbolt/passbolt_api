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

namespace Passbolt\Scim\Identifier\Resolver;

use App\Model\Entity\Role;
use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Scim\Service\ScimGetSettingsService;

class ScimResolver implements ResolverInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function find(array $conditions, string $type = self::TYPE_AND): ArrayAccess|array|null
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $settings */
        $settings = $scimSettingsTable->find()->first();
        if (!$settings) {
            return null;
        }

        $scimConfig = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        if ($scimConfig['setting_id'] !== Configure::read('Scim.settingId')) {
            //TODO Check if we want to notify admin if a wrong setting is passed
            return null;
        }

        if (
            $conditions['secret_token'] === $scimConfig['secret_token'] &&
            !empty($scimConfig['scim_user_id'])
        ) {
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = $this->getTableLocator()->get('Users');

            return $Users->findIndex(Role::GUEST)->where([
                $Users->aliasField('id') => $scimConfig['scim_user_id'],
            ])->first();
        }

        return null;
    }
}
