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
 * @since         3.8.1
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use EmailQueue\Model\Table\EmailQueueTable;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport;
use Passbolt\SmtpSettings\Service\SmtpSettingsFormatSenderInEmailQueueService;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsFormatSenderInEmailQueueService
 */
class SmtpSettingsFormatSenderOnEmailQueueServiceTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpSettingsFormatSenderInEmailQueueService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsFormatSenderInEmailQueueService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSmtpSettingsFormatSenderOnEmailQueueService_Valid_File_Source()
    {
        $fromEmail = 'foo';
        $fromName = 'bar';

        EmailQueueFactory::make([
            'from_email' => $fromEmail,
            'from_name' => $fromName,
        ])->persist();

        $transport = new SmtpTransport();
        $this->service->formatSender($transport);

        // Assert that no formatting is made when retrieving emails
        $email = $this->service->getEmailQueueTable()->find()->firstOrFail();
        $this->assertSame($fromEmail, $email->get('from_email'));
        $this->assertSame($fromName, $email->get('from_name'));
    }

    public function testSmtpSettingsFormatSenderOnEmailQueueService_Valid_DB_Source()
    {
        EmailQueueFactory::make()->persist();

        $fromEmail = 'foo@test.test';
        $fromName = 'bar';
        $data = $this->getSmtpSettingsData();
        $data['sender_name'] = $fromName;
        $data['sender_email'] = $fromEmail;
        $this->encryptAndPersistSmtpSettings($data);
        $this->assertSame(1, OrganizationSettingFactory::count());

        $transport = new SmtpTransport();
        $this->service->formatSender($transport);

        // Assert that the settings in the DB are mapped onto the email "from" fields
        $email = $this->service->getEmailQueueTable()->find()->firstOrFail();
        $this->assertSame($fromEmail, $email->get('from_email'));
        $this->assertSame($fromName, $email->get('from_name'));

        // Assert that the formatting does not impact other tables
        UserFactory::make()->persist();
        $user = TableRegistry::getTableLocator()->get('Users')->find()->firstOrFail();
        $this->assertNull($user->get('from_email'));
        $this->assertNull($user->get('from_name'));
    }

    /**
     * This is for documentation-sake: the service should apply the beforeFind on
     * the correct table in the registry
     */
    public function testSmtpSettingsFormatSenderOnEmailQueueService_Target_Correct_Table()
    {
        $this->assertSame(
            TableRegistry::getTableLocator()->get('EmailQueue', ['className' => EmailQueueTable::class]),
            $this->service->getEmailQueueTable()
        );
    }
}
