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
 * @since         3.3.0
 */
namespace App\Test\Lib\Model;

use App\Notification\Email\EmailSubscriptionDispatcher;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\View\ViewBuilder;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

trait EmailQueueTrait
{
    /**
     * @var array
     */
    protected $backupEmailNotificationSettings = [];

    /**
     * Asserts that an email with given properties is in the email queue.
     */
    protected function assertEmailIsInQueue(array $properties)
    {
        $this->assertTrue(EmailQueueFactory::find()->where($properties)->count() === 1, 'The email is not in the email queue.');
    }

    /**
     * Asserts that an email with given properties is not in the email queue.
     */
    protected function assertEmailIsNotInQueue(array $properties)
    {
        $this->assertTrue(EmailQueueFactory::find()->where($properties)->count() === 0, 'The email is not in the email queue.');
    }

    /**
     * Asserts that an email with given recipient is in the email queue.
     */
    protected function assertEmailWithRecipientIsInQueue(string $email)
    {
        $this->assertEmailIsInQueue(compact('email'));
    }

    /**
     * Asserts that an email with given recipient is not in the email queue.
     */
    protected function assertEmailWithRecipientIsInNotQueue(string $email)
    {
        $this->assertEmailIsNotInQueue(compact('email'));
    }

    /**
     * Asserts that n emails are in the email queue.
     */
    protected function assertEmailQueueCount(int $n)
    {
        $this->assertSame($n, EmailQueueFactory::count());
    }

    /**
     * Asserts that no email is in the email queue.
     */
    protected function assertEmailQueueIsEmpty()
    {
        $this->assertEmailQueueCount(0);
    }

    /**
     * Not all email notifications are activated per default.
     * Particularly on the cloud.
     * This method enables the activation of the provided email notification setting
     *
     * @param string $notificationSettingPath Notification path
     * @param bool $value Value to assign
     * @return void
     */
    protected function setEmailNotificationsSetting(string $notificationSettingPath, bool $value): void
    {
        EmailNotificationSettings::flushCache();
        $this->backupEmailNotificationSettings = array_merge_recursive(
            Configure::read('passbolt.email.send'),
            $this->backupEmailNotificationSettings
        );
        Configure::write('passbolt.email.send.' . $notificationSettingPath, $value);
    }

    /**
     * Whenever an email notification setting is set with setEmailNotificationsSetting, this method will restore the
     * settings to settings value prior to the test. This is necessary to ensute test independence
     */
    protected function restoreEmailNotificationsSettings(): void
    {
        Configure::write('passbolt.email.send', $this->backupEmailNotificationSettings);
        EmailNotificationSettings::flushCache();
    }

    /**
     * Asserts that all emails of a given recipient have a locale set and equal to the expectation.
     *
     * @param string $email Recipient
     * @param string $expectedLocale Expected locale
     */
    protected function assetEmailLocale(string $email, string $expectedLocale)
    {
        $emails = EmailQueueFactory::find()->where(compact('email'));
        $this->assertTrue($emails->count() > 0);
        foreach ($emails as $email) {
            $this->assertTextEquals($expectedLocale, $email->get('template_vars')['locale']);
        }
    }

    /**
     * Asserts that all emails of a given recipient have expected subject.
     *
     * @param string $email Recipient
     * @param string $expectedSubject Expected subject
     */
    protected function assetEmailSubject(string $email, string $expectedSubject)
    {
        $emails = EmailQueueFactory::find()->where(compact('email'));
        $this->assertTrue($emails->count() > 0);
        foreach ($emails as $email) {
            $this->assertTextEquals($expectedSubject, $email->get('subject'));
        }
    }

    /**
     * Asserts that a string is found in an email of the email queue.
     *
     * @param string $string String to search for
     * @param int|string $i Email position in the queue (start with 0), default 0, or the username of the recipient
     * @param string $message Error message
     */
    protected function assertEmailInBatchContains(string $string, $i = 0, string $message = ''): void
    {
        $this->assertStringContainsString($string, $this->renderEmail($i), $message);
    }

    /**
     * Asserts that a string is not found in an email of the email queue.
     *
     * @param string $string String to search for
     * @param int|string $i Email position in the queue (start with 0), default 0, or the username of the recipient
     * @param string $message Error message
     */
    protected function assertEmailInBatchNotContains(string $string, $i = 0, string $message = ''): void
    {
        $this->assertStringNotContainsString($string, $this->renderEmail($i), $message);
    }

    /**
     * @param int|string $i Email position in batch or recipient
     * @return string
     */
    protected function renderEmail($i = 0): string
    {
        if (is_int($i)) {
            $email = EmailQueueFactory::find()->order('id')->offset($i)->first();
        } else {
            $email = EmailQueueFactory::find()->where(['email' => $i])->first();
        }
        if (empty($email)) {
            $this->fail("The email queue does not have an email at index $i");
        }

        // Get template, template vars, subject and format
        $format = $email->get('format');
        $viewBuilder = new ViewBuilder();
        $viewBuilder->setVar('title', $email->get('subject'));
        $viewBuilder->setVar('body', $email->get('template_vars')['body']);

        return $viewBuilder
            ->setLayout('default')
            ->setLayoutPath("email/$format")
            ->setTemplate($email->get('template'))
            ->setTemplatePath("email/$format")
            ->build()
            ->render();
    }

    /**
     * Render all the emails in the queue.
     * Useful to spot errors in each mail.
     *
     * @return void
     */
    protected function renderAllEmails(): void
    {
        $emailCount = ConnectionManager::get('test')
            ->newQuery()
            ->select('*')
            ->from('email_queue')
            ->execute()
            ->rowCount();
        for ($i = 0; $i < $emailCount; $i++) {
            $this->renderEmail($i);
        }
    }

    /**
     * Helper to collect all the emails redactor. This is called in Application.php
     * When testing emails in a test service, you may call this after all plugins have been loaded.
     *
     * In addition, we load the plugins that are required for the emails to work properly
     *
     * @param array $plugins Plugins to load. Needed to load email redactor pools
     * @return void
     */
    protected function initEmailForServiceTest(array $plugins = []): void
    {
        $this->loadPlugins(array_merge($plugins, ['Passbolt/Locale', 'Passbolt/EmailDigest',]));
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();
    }

    /**
     * Deletes all emails in the queue.
     *
     * @return void
     */
    protected function deleteEmailQueue(): void
    {
        ConnectionManager::get('test')
            ->newQuery()
            ->delete('email_queue')
            ->execute();
    }
}
