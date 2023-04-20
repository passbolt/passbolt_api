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

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\LocaleService;

class LocaleRenderListener implements EventListenerInterface
{
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
            'View.beforeRender' => 'setLocaleBeforeRenderIfDefinedInTemplateVar',
            'View.afterLayout' => 'resetLocaleAfterLayoutIfDefinedInTemplateVar',
        ];
    }

    /**
     * Sets the locale if defined in the view variables.
     * This is required when sending emails.
     * Note that this is enables for CLI tools only  and at this point no user is logged in.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     * @see LocaleEmailQueueListener::setLocaleInEmailTemplateVars()
     */
    public function setLocaleBeforeRenderIfDefinedInTemplateVar(EventInterface $event): void
    {
        /** @var \Cake\View\View $View */
        $View = $event->getSubject();
        $locale = $View->get(LocaleEmailQueueListener::VIEW_VAR_KEY);
        $service = new LocaleService();
        if ($service->isValidLocale($locale)) {
            $service->setLocale($locale);
        }
    }

    /**
     * Reset the locale to the organization default locale.
     *
     * @return void
     */
    public function resetLocaleAfterLayoutIfDefinedInTemplateVar(): void
    {
        (new LocaleService())->setLocale(GetOrgLocaleService::getLocale());
    }
}
