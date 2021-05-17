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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Event;

use App\Model\Entity\OrganizationSetting;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\Locale\Service\LocaleService;

class ValidateLocaleOnBeforeSaveListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.beforeSave' => 'beforeSaveLocaleSetting',
        ];
    }

    /**
     * Validate and dasherize the locale before saving.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @return void
     */
    public function beforeSaveLocaleSetting(EventInterface $event, EntityInterface $entity): void
    {
        if (!$this->isLocaleSetting($entity)) {
            return;
        }

        $localeService = new LocaleService();

        // Validate the value
        if (!$localeService->isValidLocale($entity->get('value'))) {
            $entity->setError('value', __('This is not a valid {0}.', 'locale'));
            $event->stopPropagation();

            return;
        }

        // Dasherize the value before save
        $entity->set(
            'value',
            $localeService->dasherizeLocale($entity->get('value'))
        );
    }

    /**
     * Check that the entity is either an account setting or an organization setting
     * and that the property is locale.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity to assess
     * @return bool
     */
    public function isLocaleSetting(EntityInterface $entity): bool
    {
        if ($entity instanceof AccountSetting || $entity instanceof OrganizationSetting) {
            if ($entity->get('property') === LocaleService::SETTING_PROPERTY) {
                return true;
            }
        }

        return false;
    }
}
