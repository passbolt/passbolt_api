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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Service\Settings;

use App\Error\Exception\CustomValidationException;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Form\OfflineSettingsFormInterface;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineMode\Model\Entity\OfflineModeSetting;

class OfflineSettingsSetService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const EVENT_SETTINGS_UPDATED = 'OfflineSettings.afterSet.success';

    private OfflineSettingsFormInterface $form;

    /**
     * @param \Passbolt\OfflineMode\Form\OfflineSettingsFormInterface $form The settings form.
     */
    public function __construct(OfflineSettingsFormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * @param \App\Utility\ExtendedUserAccessControl $uac Acting user.
     * @param array $data Raw payload.
     * @return \Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto
     * @throws \Cake\Http\Exception\ForbiddenException When the user is not an admin.
     * @throws \App\Error\Exception\CustomValidationException When the payload fails form validation.
     */
    public function set(ExtendedUserAccessControl $uac, array $data): OfflineSettingsDto
    {
        $uac->assertIsAdmin();

        if (!$this->form->execute($data)) {
            throw new CustomValidationException(
                __('Could not validate offline settings data.'),
                $this->form->getErrors(),
            );
        }

        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $offlineModeSettingsTable */
        $offlineModeSettingsTable = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings');
        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting $entity */
        $entity = $offlineModeSettingsTable->createOrUpdateSetting(
            OfflineModeSetting::PROPERTY_NAME,
            $this->form->getSettings(),
            $uac,
        );

        $dto = OfflineSettingsDto::createFromEntity($entity);

        $this->dispatchEvent(self::EVENT_SETTINGS_UPDATED, compact('dto', 'uac'));

        return $dto;
    }
}
