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

namespace Passbolt\PasswordExpiry\Service\Settings;

use App\Error\Exception\FormValidationException;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

class PasswordExpirySetSettingsService extends PasswordExpirySettingsAbstractService implements PasswordExpirySetSettingsServiceInterface // phpcs:ignore
{
    use EventDispatcherTrait;

    /**
     * Event name. Fired after password expiry settings has been saved.
     *
     * @var string
     */
    public const EVENT_SETTINGS_UPDATED = 'Service.PasswordExpirySetSettingsService.updated';

    /**
     * @inheritDoc
     */
    final public function createOrUpdate(ExtendedUserAccessControl $uac, array $data): PasswordExpirySettingsDto
    {
        $form = $this->getForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the password expiry settings.'), $form);
        }

        /** @var \Passbolt\PasswordExpiry\Model\Table\PasswordExpirySettingsTable $passwordExpirySettingsTable */
        $passwordExpirySettingsTable = $this->fetchTable('Passbolt/PasswordExpiry.PasswordExpirySettings');

        /** @var \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting $passwordExpirySetting */
        $passwordExpirySetting = $passwordExpirySettingsTable->createOrUpdateSetting(
            $passwordExpirySettingsTable->getProperty(),
            $this->createDTOFromArray($form->getData())->getValue(),
            $uac
        );

        /** Dispatch settings updated event. */
        $this->dispatchEvent(self::EVENT_SETTINGS_UPDATED, compact('uac'), $passwordExpirySetting);

        return $this->createDTOFromEntity($passwordExpirySetting, $form);
    }
}
