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

use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MinimumThresholdSwitchDigestMarshaller;
use PHPUnit\Framework\MockObject\MockObject;

class MinimumThresholdSwitchDigestMarshallerTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var MockObject|DigestMarshallerInterface
     */
    private $atThresholdDigestMarshallerdMock;
    /**
     * @var MockObject|DigestMarshallerInterface
     */
    private $belowThresholdDigestMarshallerdMock;

    /**
     * @var MinimumThresholdSwitchDigestMarshaller
     */
    private $sut;

    public function setUp()
    {
        $this->atThresholdDigestMarshallerdMock = $this->createMock(DigestMarshallerInterface::class);
        $this->belowThresholdDigestMarshallerdMock = $this->createMock(DigestMarshallerInterface::class);
        $minimumThreshold = 5;

        $this->sut = new MinimumThresholdSwitchDigestMarshaller($this->atThresholdDigestMarshallerdMock, $this->belowThresholdDigestMarshallerdMock, $minimumThreshold);
    }

    /**
     * @dataProvider provideEmailEntitiesForBelowThresholdScenarios
     * @param array $emailEntities
     */
    public function testThatMarshallDigestsUseAllEmailsWithBelowThresholdDigestMarshallerWhenThresholdIsNotReached(array $emailEntities)
    {
        $expectedDigests = [$this->createEmailDigest()];

        $this->addEmailsToMarshaller($emailEntities, $this->sut);

        $this->assertEmailsAreAddedToMarshaller($emailEntities, $this->belowThresholdDigestMarshallerdMock);
        $this->assertDigestsAreMarshalledByGivenMarshallerAndReturnExpectedDigests($expectedDigests, $this->belowThresholdDigestMarshallerdMock);
        $this->assertSame($expectedDigests, $this->sut->marshalDigests());
    }

    public function provideEmailEntitiesForBelowThresholdScenarios()
    {
        return [
            "with no emails" => [
                [],
            ],
            "with number of emails below threshold" => [
                $this->createEmailQueueEntities(4),
            ],
            "with exact number of emails for threshold" => [
                $this->createEmailQueueEntities(5),
            ],
        ];
    }

    /**
     * @dataProvider provideEmailEntitiesForAtThresholdScenarios
     * @param array $emailEntities
     */
    public function testThatMarshallDigestsUseAllEmailsWithAtThresholdDigestMarshallerWhenThresholdIsReached(array $emailEntities)
    {
        $expectedDigests = [$this->createEmailDigest()];

        $this->addEmailsToMarshaller($emailEntities, $this->sut);

        $this->assertEmailsAreAddedToMarshaller($emailEntities, $this->atThresholdDigestMarshallerdMock);
        $this->assertDigestsAreMarshalledByGivenMarshallerAndReturnExpectedDigests($expectedDigests, $this->atThresholdDigestMarshallerdMock);
        $this->assertSame($expectedDigests, $this->sut->marshalDigests());
    }

    public function provideEmailEntitiesForAtThresholdScenarios()
    {
        return [
            "with number of emails above threshold" => [
                $this->createEmailQueueEntities(6),
            ],
        ];
    }

    /**
     * @dataProvider provideCanMarshalDigestsStatus
     */
    public function testThatCanMarshallDigestsReturnTrueOnlyIfBothMarshallersCanMarshall(
        bool $belowThresholdMarshallerCanMarshall,
        bool $atThresholdMarshallerCanMarshall,
        bool $expectedCanMarshallDigest
    ) {
        $this->belowThresholdDigestMarshallerdMock->expects($this->any())
            ->method('canMarshalDigestsFrom')
            ->willReturn($belowThresholdMarshallerCanMarshall);

        $this->atThresholdDigestMarshallerdMock->expects($this->any())
            ->method('canMarshalDigestsFrom')
            ->willReturn($atThresholdMarshallerCanMarshall);

        $canMarshallDigests = $this->sut->canMarshalDigestsFrom($this->createEmailQueueEntity());

        $this->assertSame($expectedCanMarshallDigest, $canMarshallDigests);
    }

    public function provideCanMarshalDigestsStatus()
    {
        return [
            "when both marshallers can marshal, can marshalDigests should return true" => [true, true, true],
            "when only below threshold marshaller can marshal, can marshalDigests should return false" => [false, true, false],
            "when only at threshold marshaller can marshal, can marshalDigests should return false" => [true, false, false],
            "when no marshallers can marshal, can marshalDigests should return false" => [false, false, false],
        ];
    }

    private function assertDigestsAreMarshalledByGivenMarshallerAndReturnExpectedDigests(array $expectedDigests, MockObject $marshallerMock)
    {
        $marshallerMock->expects($this->once())
            ->method('marshalDigests')
            ->willReturn($expectedDigests);
    }

    private function assertEmailsAreAddedToMarshaller(array $emailEntities, MockObject $marshallerMock)
    {
        $marshallerMock->expects($this->exactly(count($emailEntities)))
            ->method('addEmailEntityToMarshal')
            ->withConsecutive(...$emailEntities);
    }

    private function addEmailsToMarshaller(array $emailEntities, DigestMarshallerInterface $marshaller)
    {
        foreach ($emailEntities as $emailEntity) {
            $marshaller->addEmailEntityToMarshal($emailEntity);
        }
    }

    private function createEmailQueueEntities(int $numberOfEmailsToCreate)
    {
        $emails = [];
        for ($i = 0; $i < $numberOfEmailsToCreate; $i++) {
            $emails[] = $this->createEmailQueueEntity();
        }

        return $emails;
    }
}
