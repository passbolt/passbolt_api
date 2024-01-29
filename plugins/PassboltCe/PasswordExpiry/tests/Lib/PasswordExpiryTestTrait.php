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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\Lib;

use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

trait PasswordExpiryTestTrait
{
    /**
     * @return array
     */
    public function getDefaultPasswordExpirySettings(): array
    {
        return PasswordExpirySettingFactory::make()->getEntity()->get('value');
    }

    public function assertPasswordExpirySettingsMatchesEntity(PasswordExpirySetting $entity, array $setting): void
    {
        $this->assertSame($entity->get('id'), $setting['id']);
        $this->assertSame($entity->get('value')[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY], $setting[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY]);
        $this->assertSame($entity->get('value')[PasswordExpirySettingsDto::AUTOMATIC_UPDATE], $setting[PasswordExpirySettingsDto::AUTOMATIC_UPDATE]);
        $this->assertSame($entity->created->format('yyyy-MM-dd HH:mm:ss'), $setting['created']->format('yyyy-MM-dd HH:mm:ss'));
        $this->assertSame($entity->get('created_by'), $setting['created_by']);
        $this->assertSame($entity->modified->format('yyyy-MM-dd HH:mm:ss'), $setting['modified']->format('yyyy-MM-dd HH:mm:ss'));
        $this->assertSame($entity->get('modified_by'), $setting['modified_by']);
    }

    /**
     * @return bool[]
     */
    protected function getValidPasswordExpiryPayload(): array
    {
        return [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
        ];
    }
}
