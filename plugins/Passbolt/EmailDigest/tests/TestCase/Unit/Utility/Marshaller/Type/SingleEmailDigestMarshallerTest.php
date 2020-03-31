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
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailPreview;
use Passbolt\EmailDigest\Utility\Marshaller\Type\SingleEmailDigestMarshaller;
use PHPUnit\Framework\MockObject\MockObject;

class SingleEmailDigestMarshallerTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var SingleEmailDigestMarshaller
     */
    private $sut;

    /**
     * @var MockObject|EmailPreviewFactory
     */
    private $emailPreviewFactoryMock;

    public function setUp()
    {
        $this->emailPreviewFactoryMock = $this->createMock(EmailPreviewFactory::class);
        $this->sut = new SingleEmailDigestMarshaller($this->emailPreviewFactoryMock);
    }

    public function provideEmailEntities()
    {
        return [
            [
                [
                    $this->createEmailQueueEntity(),
                    $this->createEmailQueueEntity(),
                    $this->createEmailQueueEntity(),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideEmailEntities
     * @param array $emailEntities
     */
    public function testThatCanMarshalDigestsSupportAnyEmailEntity(array $emailEntities)
    {
        $this->assertTrue($this->sut->canMarshalDigestsFrom($emailEntities[0]));
    }

    /**
     * @dataProvider provideEmailEntities
     * @param array $emailEntities
     */
    public function testAddEmailEntityReturnInstanceOfItself(array $emailEntities)
    {
        foreach ($emailEntities as $emailEntity) {
            $this->assertSame($this->sut, $this->sut->addEmailEntityToMarshal($emailEntity));
        }
    }

    /**
     * @dataProvider provideEmailEntities
     * @param array $emailEntities
     */
    public function testMarshalDigestsCreateAsManyDigestsAsThereIsEmails(array $emailEntities)
    {
        foreach ($emailEntities as $emailEntity) {
            $this->sut->addEmailEntityToMarshal($emailEntity);
        }

        $this->emailPreviewFactoryMock->expects($this->exactly(3))
            ->method('renderEmailPreviewFromEmailEntity')
            ->willReturn(new EmailPreview('headers', 'content'));

        $expectedEmailDigestsCount = count($emailEntities);

        $digests = $this->sut->marshalDigests();

        $this->assertCount($expectedEmailDigestsCount, $digests);
    }
}
