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

namespace Passbolt\EmailDigest\Test\TestCase\Utility\Marshaller\Type;

use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;
use Passbolt\EmailDigest\Utility\Marshaller\Type\PoolDigestMarshaller;
use PHPUnit\Framework\MockObject\MockObject;

class PoolDigestMarshallerTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var PoolDigestMarshaller
     */
    private $sut;

    /**
     * @var MockObject|DigestMarshallerPool
     */
    private $digestMarshallerPoolMock;

    public function setUp()
    {
        $this->digestMarshallerPoolMock = $this->createMock(DigestMarshallerPool::class);

        $this->sut = new PoolDigestMarshaller($this->digestMarshallerPoolMock);
    }

    public function testThatCanMarshallDigestReturnTrueWhenDigestIsSupported()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest marshaller lambda which can support the email entity
        $this->digestMarshallerPoolMock->expects($this->once())
            ->method('getDigestMarshallers')
            ->willReturn([
                $this->createMarshaller(true, []),
            ]);

        $this->assertTrue($this->sut->canMarshalDigestsFrom($emailEntity), "Marshaller should support the given email.");
    }

    public function testThatCanMarshallDigestReturnFalseWhenDigestIsNotSupported()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest marshaller lambda which can support the email entity
        $this->digestMarshallerPoolMock->expects($this->once())
            ->method('getDigestMarshallers')
            ->willReturn([
                $this->createMarshaller(false, []),
            ]);

        $this->assertFalse($this->sut->canMarshalDigestsFrom($emailEntity), "Marshaller should not support the given email.");
    }

    /**
     * @throws UnsupportedEmailDigestDataException
     */
    public function testThatAddEmailEntityAddTheEmailOnlyToTheFirstPickedMarshallerInThePool()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We initialize 2 marshallers, only 1 of them is expected to be called
        $marshallerWhichShouldBeCalled = $this->createMock(DigestMarshallerInterface::class);
        $marshallerWhichShouldNotBeCalled = $this->createMock(DigestMarshallerInterface::class);

        $this->digestMarshallerPoolMock->expects($this->once())
            ->method('getDigestMarshallers')
            ->willReturn([
                $marshallerWhichShouldBeCalled,
                $marshallerWhichShouldNotBeCalled,
            ]);

        // We set expectations on how the defined marshallers should be called
        $this->assertMarshallerWillAddEmailEntity($marshallerWhichShouldBeCalled, $emailEntity);
        $this->assertMarshallerWillNeverAddEmailEntity($marshallerWhichShouldNotBeCalled, $emailEntity);

        $this->sut->addEmailEntityToMarshal($emailEntity);
    }

    public function testThatAddEmailEntityThrowUnsupportedEmailDigestExceptionWhenNoMarshallerSupportsEmail()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest marshaller lambda which can support the email entity
        $this->digestMarshallerPoolMock->expects($this->once())
            ->method('getDigestMarshallers')
            ->willReturn([
                $this->createMarshaller(false, []),
            ]);

        $this->expectException(UnsupportedEmailDigestDataException::class);

        $this->sut->addEmailEntityToMarshal($emailEntity);
    }

    public function testThatMarshalDigestsMergeDigestsFromDifferentMarshallersTogether()
    {
        $emailDigestsFromMarshallerA = [
            $this->createEmailDigest(),
        ];
        $emailDigestsFromMarshallerB = [
            $this->createEmailDigest(),
        ];

        // We create 2 digests marshallers which return their digests
        $this->digestMarshallerPoolMock->expects($this->once())
            ->method('getDigestMarshallers')
            ->willReturn([
                $this->createMarshaller(true, $emailDigestsFromMarshallerA),
                $this->createMarshaller(true, $emailDigestsFromMarshallerB),
            ]);

        $expectedDigests = array_merge($emailDigestsFromMarshallerA, $emailDigestsFromMarshallerB);

        $this->assertSame($expectedDigests, $this->sut->marshalDigests());
    }

    private function assertMarshallerWillAddEmailEntity(MockObject $digestMarshaller, Entity $emailEntity)
    {
        $digestMarshaller->expects($this->once())
            ->method('addEmailEntityToMarshal')
            ->with($emailEntity)
            ->willReturn(true);

        $digestMarshaller->expects($this->once())
            ->method('canMarshalDigestsFrom')
            ->willReturn(true);
    }

    private function assertMarshallerWillNeverAddEmailEntity(MockObject $digestMarshaller, Entity $emailEntity)
    {
        $digestMarshaller->expects($this->never())
            ->method('addEmailEntityToMarshal')
            ->with($emailEntity)
            ->willReturn(true);

        $digestMarshaller->expects($this->never())
            ->method('canMarshalDigestsFrom')
            ->willReturn(true);
    }
}
