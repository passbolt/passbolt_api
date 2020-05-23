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
 * @since         2.13.0
 */
namespace Passbolt\EmailDigest\Test\TestCase\Unit\Service;

use App\Test\Fixture\Base\EmailQueueFixture;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Network\Exception\SocketException;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestEmailTransport;
use EmailQueue\Model\Table\EmailQueueTable;
use Passbolt\EmailDigest\Service\EmailDigestService;
use Passbolt\EmailDigest\Service\SendEmailBatchService;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;

class SendEmailBatchServiceTest extends AppIntegrationTestCase
{
    use EmailDigestMockTestTrait;
    use EmailTrait;

    public $fixtures = [
        EmailQueueFixture::class,
    ];

    /**
     * @var SendEmailBatchService
     */
    private $sut;

    /**
     * @var MockObject|EmailDigestService
     */
    private $emailDigestServiceMock;

    /**
     * @var EmailQueueTable|MockObject
     */
    private $emailQueueTableMock;

    public function setUp()
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/EmailDigest']);

        // Mock the digest service
        $this->emailDigestServiceMock = $this->createMock(EmailDigestService::class);

        // Mock a model, maintain fixtures and table association
        $this->emailQueueTableMock = $this->getMockForModel('EmailQueue.EmailQueue', [
            'getBatch',
            'success',
            'fail',
            'releaseLocks',
        ])->setTable('email_queue'); // the CakePHP getMockForModel method use Inflector:tableize() method which make the table name plural

        self::setupTransports();

        $this->sut = new SendEmailBatchService(
            $this->emailQueueTableMock,
            $this->emailDigestServiceMock
        );
    }

    public function testSendEmailBatchService_MarkAsSentWhenSendDigestSucceed()
    {
        // Define the list of email queue entity ids that should be marked as success at the end of the test
        $expectedEmailsIdsToUpdate = [[1], [2]];

        // We create emails entities from email-queue plugin
        $emailQueueEntities = [
            $this->createEmailQueueEntity(['id' => 1]),
            $this->createEmailQueueEntity(['id' => 2]),
        ];

        // Simulate the getBatch method from the EmailQueue model
        $this->emailQueueTableMock->expects($this->once())
            ->method('getBatch')
            ->willReturn($emailQueueEntities);

        // We mock the digest services and make it returns digests
        // It is normally returned by the digests but we directly create them in this situation
        // because we do not need to test the marshalling logic in this test which is a separate concern)
        $this->emailDigestServiceMock->expects($this->once())
            ->method('createDigests')
            ->willReturn($this->createEmailDigestsFromEmailEntities($emailQueueEntities));

        // It should call the success method of the email queue for each emails
        $this->assertEmailQueueWillFlagEmailsAsSuccess($expectedEmailsIdsToUpdate);

        $this->sut->sendNextEmailsBatch();
    }

    public function testSendEmailBatchService_MarkAsNonSentWhenSendDigestFailed()
    {
        $this->makeEmailTransportFailWithException(new SocketException('Failed to send email.'));

        // Define the list of email queue entity ids that should be marked as success at the end of the test
        $expectedIdFailureMessageCouples = [
            [1, 'Failed to send email.'],
            [2, 'Failed to send email.'],
        ];

        // We create emails entities from email-queue plugin
        $emailQueueEntities = [
            $this->createEmailQueueEntity(['id' => 1]),
            $this->createEmailQueueEntity(['id' => 2]),
        ];

        // Simulate the getBatch method from the EmailQueue model
        $this->emailQueueTableMock->expects($this->once())
            ->method('getBatch')
            ->willReturn($emailQueueEntities);

        // We mock the digest services and make it returns our digests initialized earlier
        // It is normally returned by the digests but we directly create them in this situation
        // because we do not need to test the marshalling logic in this test)
        $this->emailDigestServiceMock->expects($this->once())
            ->method('createDigests')
            ->willReturn($this->createEmailDigestsFromEmailEntities($emailQueueEntities));

        $this->assertEmailQueueWillFlagEmailsAsFailed($expectedIdFailureMessageCouples);

        $this->sut->sendNextEmailsBatch();
    }

    public function testSendEmailBatchService_ReleaseLocksAfterSend()
    {
        // Define the list of email queue entity ids that should be marked as success at the end of the test
        $expectedEmailsIdsToUpdate = [1, 2];

        // We create emails entities from email-queue plugin
        $emailQueueEntities = [
            $this->createEmailQueueEntity(['id' => 1]),
            $this->createEmailQueueEntity(['id' => 2]),
        ];

        // Simulate the getBatch method from the EmailQueue model
        $this->emailQueueTableMock->expects($this->once())
            ->method('getBatch')
            ->willReturn($emailQueueEntities);

        // We mock the digest services and make it returns our digests initialized earlier
        // It is normally returned by the digests but we directly create them in this situation
        // because we do not need to test the marshalling logic in this test)
        $this->emailDigestServiceMock->expects($this->once())
            ->method('createDigests')
            ->willReturn($this->createEmailDigestsFromEmailEntities($emailQueueEntities));

        // It should call the releaseLocks method with the ids of every emails which have been sent with or without success
        $this->assertEmailQueueWillReleaseLocksForEmailIds($expectedEmailsIdsToUpdate);

        $this->sut->sendNextEmailsBatch();
    }

    private function assertEmailQueueWillFlagEmailsAsSuccess(array $expectedIdWithSuccess)
    {
        // It should call the `success` method of the email queue for each emails
        $this->emailQueueTableMock
            ->expects($this->exactly(count($expectedIdWithSuccess)))
            ->method('success')
            ->withConsecutive(...$expectedIdWithSuccess);
    }

    private function assertEmailQueueWillFlagEmailsAsFailed(array $expectedIdFailureMessageCouples)
    {
        // It should call the `fail` method of the email queue for each emails
        $this->emailQueueTableMock
            ->expects($this->exactly(count($expectedIdFailureMessageCouples)))
            ->method('fail')
            ->withConsecutive(...$expectedIdFailureMessageCouples);
    }

    private function assertEmailQueueWillReleaseLocksForEmailIds(array $emailIds)
    {
        $this->emailQueueTableMock
            ->expects($this->once())
            ->method('releaseLocks')
            ->with($emailIds);
    }

    private function createEmailDigestsFromEmailEntities(array $emailEntities)
    {
        return [$this->createEmailDigest($emailEntities)];
    }

    /**
     * Replace the transport method for the email with a Transport class which raises an exception when sending the email.
     * This is useful to simulate a failure during send and assert on that.
     */
    private function makeEmailTransportFailWithException(Throwable $exception)
    {
        $configuredTransports = TransportFactory::configured();
        foreach ($configuredTransports as $configuredTransport) {
            $config = TransportFactory::getConfig($configuredTransport);
            $config['className'] = self::class;
            $instance = new class ([], $exception) extends TestEmailTransport
            {
                public function __construct($config, $exception)
                {
                    $this->exception = $exception;
                    parent::__construct($config);
                }

                public function send(Email $email)
                {
                    throw $this->exception;
                }
            };
            TransportFactory::drop($configuredTransport);
            TransportFactory::setConfig($configuredTransport, $instance);
        }
    }
}
