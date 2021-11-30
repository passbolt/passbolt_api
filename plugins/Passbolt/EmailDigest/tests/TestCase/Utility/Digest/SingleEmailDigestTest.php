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

namespace Passbolt\EmailDigest\Test\TestCase\Utility\Digest;

use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Digest\SingleDigest;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailPreview;

class SingleEmailDigestTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\DigestInterface
     */
    private $sut;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $emailPreviewFactoryMock;

    public function setUp(): void
    {
        $this->emailPreviewFactoryMock = $this->createMock(EmailPreviewFactory::class);
        $this->sut = new SingleDigest($this->emailPreviewFactoryMock);
    }

    public function testSingleEmailDigest_CanAddToDigestAlwaysTrue()
    {
        $this->assertTrue($this->sut->canAddToDigest($this->createEmailQueueEntity()));
    }

    public function testSingleEmailDigest_AddEmailEntityReturnInstanceOfItself()
    {
        $emailEntities = [
            $this->createEmailQueueEntity(),
            $this->createEmailQueueEntity(),
            $this->createEmailQueueEntity(),
        ];
        foreach ($emailEntities as $emailEntity) {
            $this->assertSame($this->sut, $this->sut->addEmailEntity($emailEntity));
        }
    }

    public function testSingleEmailDigest_CreateAsManyEmailDigestsAsThereAreEmails()
    {
        $emailEntities = [
            $this->createEmailQueueEntity(),
            $this->createEmailQueueEntity(),
            $this->createEmailQueueEntity(),
        ];
        foreach ($emailEntities as $emailEntity) {
            $this->sut->addEmailEntity($emailEntity);
        }

        $this->emailPreviewFactoryMock->expects($this->exactly(3))
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('headers', 'content'));

        $expectedEmailDigestsCount = count($emailEntities);

        $digests = $this->sut->marshalEmails();

        $this->assertCount($expectedEmailDigestsCount, $digests);
    }
}
