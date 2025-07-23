<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Passbolt\Scim\Identifier\Resolver;

use App\Model\Entity\Role;
use App\Model\Table\OrganizationSettingsTable;
use App\Model\Table\UsersTable;
use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Scim\Service\ScimBaseSettingsService;

class ScimResolver implements ResolverInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function find(array $conditions, string $type = self::TYPE_AND): ArrayAccess|array|null
    {
        /** @var OrganizationSettingsTable $table */
        $OrganizationSettings = $this->getTableLocator()->get('OrganizationSettings');

        $scimOrganizationSetting = $OrganizationSettings->getByProperty(ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME);

        if (!$scimOrganizationSetting) {
            return null;
        }

        $scimConfig = json_decode($scimOrganizationSetting->value, true);
        if ($scimConfig['setting_id'] !== Configure::read('Scim.settingId')) {
            //TODO Check if we want to notify admin if a wrong setting is passed
            return null;
        }

        if (is_array($scimConfig) &&
            $conditions['secret_token'] === $scimConfig['secret_token'] &&
            !empty($scimConfig['scim_user_id'])
        ) {
            /** @var UsersTable $Users */
            $Users = $this->getTableLocator()->get('Users');

            return $Users->findIndex(Role::GUEST)->where([
                $Users->aliasField('id') => $scimConfig['scim_user_id']
            ])->first();
        }

        return null;
    }
}
