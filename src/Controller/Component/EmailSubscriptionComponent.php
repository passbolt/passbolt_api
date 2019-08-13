<?php

namespace App\Controller\Component;

use App\Notification\Email\EmailSender;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\Email\EmailSubscriptionManager;
use Cake\Controller\Component;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use EmailQueue\Model\Table\EmailQueueTable;

class EmailSubscriptionComponent extends Component
{
    const APP_FULL_BASE_URL = 'App.fullBaseUrl';

    /**
     * @var EmailSubscriptionDispatcher
     */
    private $emailSubscriptionDispatcher;

    use EventDispatcherTrait;

    public function initialize(array $config)
    {
        $this->emailSubscriptionDispatcher = new EmailSubscriptionDispatcher(
            EventManager::instance(),
            new EmailSubscriptionManager(),
            new EmailSender(EventManager::instance(), $this->getEmailQueueTable(), $config[self::APP_FULL_BASE_URL])
        );

        parent::initialize($config);
    }

    public function beforeFilter()
    {
        $this->run();
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->emailSubscriptionDispatcher->collect();
    }

    /**
     * @return EmailQueueTable
     */
    private function getEmailQueueTable()
    {
        return TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
    }
}
