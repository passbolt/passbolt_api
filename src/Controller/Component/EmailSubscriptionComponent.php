<?php
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
 * @since         2.0.0
 */

namespace App\Controller\Component;

use App\Notification\Email\EmailSender;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\Email\EmailSubscriptionManager;
use Cake\Controller\Component;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;

class EmailSubscriptionComponent extends Component
{
    use EventDispatcherTrait;

    const APP_FULL_BASE_URL = 'App.fullBaseUrl';
    const PASSBOLT_EMAIL_PURIFY_SUBJECT = 'passbolt.email.purify.subject';

    /**
     * @var EmailSubscriptionDispatcher
     */
    private $emailSubscriptionDispatcher;

    /**
     * @param array $config Config for the component
     * @return void
     */
    public function initialize(array $config)
    {
        $this->emailSubscriptionDispatcher = new EmailSubscriptionDispatcher(
            EventManager::instance(),
            new EmailSubscriptionManager(),
            new EmailSender(
                TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue'),
                $this->getConfig(self::APP_FULL_BASE_URL),
                $this->getConfig(self::PASSBOLT_EMAIL_PURIFY_SUBJECT) ?? false
            )
        );

        parent::initialize($config);
    }

    /**
     * @return void
     */
    public function beforeFilter()
    {
        $this->emailSubscriptionDispatcher->collect();
    }
}
