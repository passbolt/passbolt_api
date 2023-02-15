<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.8.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Mailer\Transport;

use Cake\Event\EventDispatcherTrait;
use Cake\Mailer\Message;
use Cake\Mailer\Transport\SmtpTransport as CakeSmtpTransport;

/**
 * Send mail using SMTP protocol
 */
class SmtpTransport extends CakeSmtpTransport
{
    use EventDispatcherTrait;

    public const SMTP_TRANSPORT_INITIALIZE_EVENT = 'smtp_transport_initialize_event';
    public const SMTP_TRANSPORT_BEFORE_SEND_EVENT = 'smtp_transport_before_send_event';

    /**
     * Triggers the initialize event
     *
     * @param array $config default configs
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->dispatchEvent(self::SMTP_TRANSPORT_INITIALIZE_EVENT, $config, $this);
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): array
    {
        $this->dispatchSmtpTransportSendEvent($message);

        return parent::send($message);
    }

    /**
     * @param \Cake\Mailer\Message $message Message
     * @return void
     */
    protected function dispatchSmtpTransportSendEvent(Message $message): void
    {
        $this->dispatchEvent(self::SMTP_TRANSPORT_BEFORE_SEND_EVENT, [], $message);
    }
}
