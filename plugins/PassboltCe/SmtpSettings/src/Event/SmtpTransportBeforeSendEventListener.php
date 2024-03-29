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
 * @since         3.11.0
 */
namespace Passbolt\SmtpSettings\Event;

use App\Mailer\Transport\SmtpTransport;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;
use Passbolt\SmtpSettings\Service\SmtpSettingsSslOptionsGetService;

class SmtpTransportBeforeSendEventListener implements EventListenerInterface
{
    /**
     * @var array|null
     */
    private $configInDB;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT => 'initializeTransport',
            SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT => 'setEmailFromIfDefinedInDB',
        ];
    }

    /**
     * @return bool
     */
    public function isSourceDB(): bool
    {
        return is_array($this->getConfigInDB());
    }

    /**
     * @return array|null
     */
    public function getConfigInDB(): ?array
    {
        return $this->configInDB;
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function initializeTransport(EventInterface $event): void
    {
        /** @var \App\Mailer\Transport\SmtpTransport $transport */
        $transport = $event->getSubject();
        $defaultConfig = $event->getData();
        $configToMerge = [];

        $this->configInDB = (new SmtpSettingsGetSettingsInDbService())->getSettings();
        if (!is_null($this->configInDB)) {
            $configToMerge = [
                'className' => self::class,
                'sender_name' => $this->configInDB['sender_name'],
                'sender_email' => $this->configInDB['sender_email'],
                'host' => $this->configInDB['host'],
                'port' => $this->configInDB['port'],
                'tls' => $this->configInDB['tls'] ?? null,
                'client' => $this->configInDB['client'],
                'username' => $this->configInDB['username'],
                'password' => $this->configInDB['password'],
            ];
        }

        // Merge SSL Options if present in config
        $sslOptions = (new SmtpSettingsSslOptionsGetService())->get();
        if (!empty($sslOptions)) {
            $configToMerge['context'] = ['ssl' => $sslOptions];
        }

        $transport->setConfig(array_merge($defaultConfig, $configToMerge));
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function setEmailFromIfDefinedInDB(EventInterface $event): void
    {
        // If the config was not defined in DB, do not set the sender
        // as it will rely on the settings on file
        if (!$this->isSourceDB()) {
            return;
        }

        $senderEmail = $this->configInDB['sender_email'];
        $senderName = $this->configInDB['sender_name'];

        /** @var \Cake\Mailer\Message $message */
        $message = $event->getSubject();
        $message->setFrom($senderEmail, $senderName);
        $message->setSender($senderEmail, $senderName);
        $message->setReturnPath($senderEmail, $senderName);
    }
}
