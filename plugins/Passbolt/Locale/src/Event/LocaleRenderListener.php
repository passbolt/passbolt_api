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
use Cake\I18n\I18n;
use Passbolt\Locale\Utility\LocaleUtility;

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
     * The locale previous to rendering is kept in memory in order
     * to be reset after the rendering is done.
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
        if (LocaleUtility::localeIsValid($locale)) {
            // Remember the locale before render.
            self::$localeBeforeRender = self::$localeBeforeRender ?? I18n::getLocale();
            LocaleUtility::setLocale($locale);
        }
    }

    /**
     * Reset the locale if set on memory prior to rendering.
     *
     * @return void
     */
    public function resetLocaleAfterLayoutIfDefinedInTemplateVar(): void
    {
        LocaleUtility::setLocaleIfValid(self::$localeBeforeRender);
        self::$localeBeforeRender = null;
    }
}
