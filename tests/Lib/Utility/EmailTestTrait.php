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
namespace App\Test\Lib\Utility;

use App\Mailer\Transport\DebugTransport;
use Cake\Mailer\Message;
use Cake\Mailer\TransportFactory;

/**
 * Make assertions on emails sent through the Cake\TestSuite\TestEmailTransport
 *
 * After adding the trait to your test case, all mail transports will be replaced
 * with TestEmailTransport which is used for making assertions and will *not* actually
 * send emails.
 */
trait EmailTestTrait
{
    /**
     * Replaces all transports with the test transport during test setup
     *
     * @before
     * @return void
     */
    public function setupTransports(): void
    {
        $configuredTransports = TransportFactory::configured();

        foreach ($configuredTransports as $configuredTransport) {
            $config = TransportFactory::getConfig($configuredTransport);
            $config['className'] = DebugTransport::class;
            TransportFactory::drop($configuredTransport);
            TransportFactory::setConfig($configuredTransport, $config);
        }
    }

    /**
     * @return DebugTransport
     */
    protected function getDebugTransport(): DebugTransport
    {
        /** @var \App\Mailer\Transport\DebugTransport $transport */
        $transport = TransportFactory::get('default');

        return $transport;
    }

    /**
     * @param int|null $at Mail position
     * @return Message
     */
    protected function getMailAt(?int $at = 0): Message
    {
        $mail = $this->getDebugTransport()->getMessages()[$at] ?? null;
        if (is_null($mail)) {
            $this->fail('No emails where sent at index ' . $at);
        }

        return $mail;
    }

    /**
     * Asserts an expected number of emails were sent
     *
     * @param int $count Email count
     * @param string $message Message
     * @return void
     */
    public function assertMailCount(int $count, string $message = ''): void
    {
        $this->assertSame($count, count($this->getDebugTransport()->getMessages()), $message);
    }

    /**
     * Asserts an email at a specific index was sent from an address
     *
     * @param int $at Email index
     * @param array $from Email address
     * @param string $message Message
     * @return void
     */
    public function assertMailSentFromAt(int $at, array $from, string $message = ''): void
    {
        $this->assertSame($from, $this->getMailAt($at)->getFrom(), $message);
    }

    /**
     * Asserts an email at a specific index was sent to an address
     *
     * @param int $at Email index
     * @param array $recipient Email address
     * @param string $message Message
     * @return void
     */
    public function assertMailSentToAt(int $at, array $recipient, string $message = ''): void
    {
        $this->assertSame($recipient, $this->getMailAt($at)->getTo(), $message);
    }

    /**
     * Asserts an email at a specific index contains expected contents
     *
     * @param int $at Email index
     * @param string $contents Contents
     * @param string $message Message
     * @return void
     */
    public function assertMailContainsAt(int $at, string $contents, string $message = ''): void
    {
        $this->assertTextContains(h($contents), $this->getMailAt($at)->getBodyString(), $message);
    }

    /**
     * Asserts an email subject contains expected contents
     *
     * @param string|int $contents Contents
     * @param string $message Message
     * @return void
     */
    public function assertMailSubjectContainsAt(int $at, string $contents, string $message = ''): void
    {
        $this->assertTextContains(h($contents), $this->getMailAt($at)->getOriginalSubject(), $message);
    }

    /**
     * Assert given string is repeated particular times.
     * Useful when you want to assert that particular HTML tag(i.e. <head>, <body>) is repeated "$count" times.
     *
     * @param int $count Checks this number of times string is repeated
     * @param string $string String/text to check.
     * @param int $at Email index (optional), by default 0. Omitted if `$content` is present.
     * @param string|null $content Content/text to check instead of email index's body.
     * @return void
     */
    public function assertMailBodyStringCount(int $count, string $string, int $at = 0, ?string $content = null): void
    {
        if (!is_string($content)) {
            $body = $this->getMailAt()->getBodyHtml();
        } else {
            $body = $content;
        }

        // Include "<", ">", "/", etc. characters to consider as string, otherwise it won't be considered.
        $words = str_word_count($body, 1, '<>/-');
        $words = array_count_values($words);

        $this->assertEquals($count, $words[$string]);
    }
}
