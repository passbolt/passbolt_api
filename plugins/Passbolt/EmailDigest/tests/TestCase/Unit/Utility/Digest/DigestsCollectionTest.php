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
 * @since         2.13.0
 */

namespace Passbolt\EmailDigest\Test\TestCase\Unit\Utility\Digest;

use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestInterface;
use Passbolt\EmailDigest\Utility\Digest\DigestsCollection;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use PHPUnit\Framework\MockObject\MockObject;

class DigestsCollectionTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var DigestsCollection
     */
    private $sut;

    /**
     * @var MockObject|DigestsPool
     */
    private $digestsCollectionMock;

    public function setUp()
    {
        $this->digestsCollectionMock = $this->createMock(DigestsPool::class);

        $this->sut = new DigestsCollection($this->digestsCollectionMock);
    }

    public function testDigestsCollection_canAddToDigestSuccess()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest lambda which can support the email entity
        $this->digestsCollectionMock->expects($this->once())
            ->method('getDigests')
            ->willReturn([
                $this->createDigest(true, []),
            ]);

        $this->assertTrue($this->sut->canAddToDigest($emailEntity), 'Marshaller should support the given email.');
    }

    public function testDigestsCollection_canAddToDigestFail()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest lambda which can support the email entity
        $this->digestsCollectionMock->expects($this->once())
            ->method('getDigests')
            ->willReturn([
                $this->createDigest(false, []),
            ]);

        $this->assertFalse($this->sut->canAddToDigest($emailEntity), 'Marshaller should not support the given email.');
    }

    /**
     * @throws UnsupportedEmailDigestDataException
     */
    public function testDigestsCollection_AddEmailEntityToFirstValidDigestInThePool()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We initialize 2 digests, only 1 of them is expected to be called
        $digestThatShouldBeCalled = $this->createMock(DigestInterface::class);
        $digestThatShouldNotBeCalled = $this->createMock(DigestInterface::class);

        $this->digestsCollectionMock->expects($this->once())
            ->method('getDigests')
            ->willReturn([
                $digestThatShouldBeCalled,
                $digestThatShouldNotBeCalled,
            ]);

        // We set expectations on how the defined digests should be called
        $this->assertDigestWillAddEmailEntity($digestThatShouldBeCalled, $emailEntity);
        $this->assertDigestWillNeverAddEmailEntity($digestThatShouldNotBeCalled, $emailEntity);

        $this->sut->addEmailEntity($emailEntity);
    }

    public function testDigestsCollection_ThrowUnsupportedEmailDigestException()
    {
        $emailEntity = $this->createEmailQueueEntity();

        // We create a digest lambda which can support the email entity
        $this->digestsCollectionMock->expects($this->once())
            ->method('getDigests')
            ->willReturn([
                $this->createDigest(false, []),
            ]);

        $this->expectException(UnsupportedEmailDigestDataException::class);

        $this->sut->addEmailEntity($emailEntity);
    }

    public function testDigestsCollection_MergeEmailDigestsFromDifferentDigestTogether()
    {
        $emailDigestsFromDigestA = [
            $this->createEmailDigest(),
        ];
        $emailDigestsFromDigestB = [
            $this->createEmailDigest(),
        ];

        // We create 2 digests which return their emails
        $this->digestsCollectionMock->expects($this->once())
            ->method('getDigests')
            ->willReturn([
                $this->createDigest(true, $emailDigestsFromDigestA),
                $this->createDigest(true, $emailDigestsFromDigestB),
            ]);

        $expectedDigests = array_merge($emailDigestsFromDigestA, $emailDigestsFromDigestB);

        $this->assertSame($expectedDigests, $this->sut->marshalEmails());
    }

    private function assertDigestWillAddEmailEntity(MockObject $digest, Entity $emailEntity)
    {
        $digest->expects($this->once())
            ->method('addEmailEntity')
            ->with($emailEntity)
            ->willReturn(true);

        $digest->expects($this->once())
            ->method('canAddToDigest')
            ->willReturn(true);
    }

    private function assertDigestWillNeverAddEmailEntity(MockObject $digest, Entity $emailEntity)
    {
        $digest->expects($this->never())
            ->method('addEmailEntity')
            ->with($emailEntity)
            ->willReturn(true);

        $digest->expects($this->never())
            ->method('canAddToDigest')
            ->willReturn(true);
    }
}
