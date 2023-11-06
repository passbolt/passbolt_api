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
 * @since         3.11.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Mailer\Transport;

use Cake\Mailer\Message;
use Cake\Mailer\Transport\DebugTransport as CakeDebugTransport;

/**
 * Debug Smtp Transport class based on the CakePHP one,
 * extending the Passbolt SmtpTransport to trigger the before send event.
 */
class DebugTransport extends SmtpTransport
{
    /**
     * @var \Cake\Mailer\Message[]
     */
    protected array $messages = [];

    /**
     * @inheritDoc
     */
    public function send(Message $message): array
    {
        $this->dispatchSmtpTransportSendEvent($message);
        $result = (new CakeDebugTransport())->send($message);
        $this->messages[] = $message;

        return $result;
    }

    /**
     * @return \Cake\Mailer\Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return void
     */
    public function clearMessages(): void
    {
        $this->messages = [];
    }

    /**
     * @return \Cake\Mailer\Message|null
     */
    public function getLastMessage(): ?Message
    {
        $n = count($this->messages);

        return $n === 0 ? null : $this->messages[$n - 1];
    }

    /**
     * Returns trace.
     * Ideally you would mock this method and then set it in TransportFactory to get the desired trace.
     *
     * @return array
     */
    public function getTrace(): array
    {
        return [];
    }
}
