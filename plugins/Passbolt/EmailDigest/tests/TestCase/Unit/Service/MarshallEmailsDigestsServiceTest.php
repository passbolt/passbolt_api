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
 * @since         2.14.0
 */

namespace Passbolt\EmailDigest\Test\TestCase\Unit\Service;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailDigest\Service\MarshallEmailsDigestsService;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Factory\DigestMarshallerFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\Type\PoolDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\SingleEmailDigestMarshaller;
use PHPUnit\Framework\MockObject\MockObject;

class MarshallEmailsDigestsServiceTest extends AppIntegrationTestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var MockObject|DigestMarshallerFactory
     */
    private $digestMarshallerFactoryMock;

    /**
     * @var MarshallEmailsDigestsService
     */
    private $sut;

    public function setUp()
    {
        $this->digestMarshallerFactoryMock = $this->createMock(DigestMarshallerFactory::class);

        $this->sut = new MarshallEmailsDigestsService($this->digestMarshallerFactoryMock);

        parent::setUp();
    }

    public function testThatEmailsAreGroupedByRecipient()
    {
        // Initialize the emails entities that we receive.
//        $emailEntities = [
//            $this->createEmailQueueEntity()
//        ];
//
//        $this->sut->createDigestsByRecipient();
        $this->markTestIncomplete();
    }

    public function testThatDefaultMarshallerIsUsedWhenEmailIsNotSupportedByMarshaller()
    {
        // Initialize the emails entities that we receive.
        $emailEntities = [
            $this->createEmailQueueEntity([], [$this->createUserForEmail('ada@passbolt.com', 'Ada Lovelace')]),
            $this->createEmailQueueEntity([], [$this->createUserForEmail('betty@passbolt.com', 'Betty Holberton')]),
        ];

        $expectedDigests = [
            new EmailDigest(),
        ];

        $poolDigestMarshallerMock = $this->createMock(PoolDigestMarshaller::class);
        $defaultDigestMarshallerMock = $this->createMock(SingleEmailDigestMarshaller::class);

        $this->digestMarshallerFactoryMock
            ->expects($this->once())
            ->method('createPoolDigestMarshaller')
            ->willReturn($poolDigestMarshallerMock);

        $this->digestMarshallerFactoryMock
            ->expects($this->once())
            ->method('createSingleEmailDigestMarshaller')
            ->willReturn($defaultDigestMarshallerMock);

        // We simulate that the pool digests marshaller can not digest the given email
        $poolDigestMarshallerMock->expects($this->any())
            ->method('canMarshalDigestsFrom')
            ->withConsecutive(...$emailEntities)
            ->willReturn(false);

        $this->assertThatMarshallWillAddEmailEntities($defaultDigestMarshallerMock, $emailEntities);
        $this->assertThatMarshallerWillNeverAddEmailEntity($poolDigestMarshallerMock);
        $this->assertThatMarshallerWillReturnExpectedDigests($defaultDigestMarshallerMock, $expectedDigests);
        // And pool digest marshallers should return nothing
        $this->assertThatMarshallerWillReturnExpectedDigests($poolDigestMarshallerMock, []);

        $digests = $this->sut->createDigestsByRecipient($emailEntities);

        $this->assertEquals([$expectedDigests], iterator_to_array($digests));
    }

    public function testThatPoolMarshallerIsUsedWhenEmailIsSupported()
    {
        // Initialize the emails entities that we receive.
        $emailEntities = [$this->createEmailQueueEntity()];
        $expectedDigests = [new EmailDigest()];
        $poolDigestMarshallerMock = $this->createMock(PoolDigestMarshaller::class);
        $defaultDigestMarshallerMock = $this->createMock(SingleEmailDigestMarshaller::class);

        $this->digestMarshallerFactoryMock
            ->expects($this->once())
            ->method('createPoolDigestMarshaller')
            ->willReturn($poolDigestMarshallerMock);

        $this->digestMarshallerFactoryMock
            ->expects($this->once())
            ->method('createSingleEmailDigestMarshaller')
            ->willReturn($defaultDigestMarshallerMock);

        // We simulate that the pool digests marshaller can digest the given emails
        $poolDigestMarshallerMock->expects($this->any())
            ->method('canMarshalDigestsFrom')
            ->withConsecutive(...$emailEntities)
            ->willReturn(true);

        // Then, the pool digest marshaller should receive new emails data
        $this->assertThatMarshallWillAddEmailEntities($poolDigestMarshallerMock, $emailEntities);
        $this->assertThatMarshallerWillNeverAddEmailEntity($defaultDigestMarshallerMock);
        $this->assertThatMarshallerWillReturnExpectedDigests($poolDigestMarshallerMock, $expectedDigests);
        // And default digest marshallers should return nothing
        $this->assertThatMarshallerWillReturnExpectedDigests($defaultDigestMarshallerMock, []);

        $digests = $this->sut->createDigestsByRecipient($emailEntities);

        $this->assertEquals([$expectedDigests], iterator_to_array($digests));
    }

    private function assertThatMarshallWillAddEmailEntities(DigestMarshallerInterface $marshaller, array $emailEntities)
    {
        $marshaller->expects($this->any())
            ->method('addEmailEntityToMarshal')
            ->withConsecutive(...$emailEntities);
    }

    /**
     * Check that the digest marshaller should never receive new emails data
     * @param DigestMarshallerInterface $marshaller
     */
    private function assertThatMarshallerWillNeverAddEmailEntity(DigestMarshallerInterface $marshaller)
    {
        $marshaller->expects($this->never())
            ->method('addEmailEntityToMarshal');
    }

    /**
     * Check that the digest marshaller should return our expected digests
     * @param DigestMarshallerInterface $marshaller
     * @param array $expectedDigests
     */
    private function assertThatMarshallerWillReturnExpectedDigests(DigestMarshallerInterface $marshaller, array $expectedDigests)
    {
        $marshaller->expects($this->once())
            ->method('marshalDigests')
            ->willReturn($expectedDigests);
    }
}
