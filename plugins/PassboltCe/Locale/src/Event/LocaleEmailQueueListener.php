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

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\Locale\Service\GetUserLocaleService;

class LocaleEmailQueueListener implements EventListenerInterface
{
    public const VIEW_VAR_KEY = 'locale';

    /**
     * @var string|null
     */
    public static $localeBeforeRender;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.beforeSave' => 'setLocaleInEmailTemplateVars',
        ];
    }

    /**
     * Whenever an email is stored in the EmailQueue,
     * the locale of the recipient, if found, is set in the View vars
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @return void
     */
    public function setLocaleInEmailTemplateVars(EventInterface $event, EntityInterface $entity): void
    {
        if ($entity->getSource() !== 'EmailQueue.EmailQueue') {
            return;
        }

        $service = new GetUserLocaleService();
        $locale = $service->getLocale($entity->get('email'));
        if (!$service->isValidLocale($locale)) {
            return;
        }

        $template_vars = $entity->get('template_vars') ?? [];
        $isArray = is_array($template_vars);
        if (!$isArray) {
            $template_vars = json_decode($template_vars, true);
        }

        $template_vars[self::VIEW_VAR_KEY] = $locale;

        if (!$isArray) {
            $template_vars = json_encode($template_vars);
        }

        $entity->set(compact('template_vars'));
    }
}
