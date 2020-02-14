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
use Passbolt\EmailDigest\Utility\Marshaller\Type\MaximumThresholdSwitchDigestMarshaller;

class MaximumThresholdSwitchDigestMarshallerTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var MaximumThresholdSwitchDigestMarshaller
     */
    private $sut;

    public function testThatMarshallDigestsUseProvidedMarshallerWhenThresholdLimitIsNotReached()
    {
        $expectedDigests = [
            $this->createEmailDigest(),
        ];

        $digestMarshaller = $this->createMarshaller(true, $expectedDigests);
        $limit = 5;
        $numberOfEmails = $limit;
        $thresholdCallback = function (Entity $emailEntity, int $count) {
            $this->fail("Threshold callback function should have not been called.");
        };

        $this->sut = new MaximumThresholdSwitchDigestMarshaller($digestMarshaller, $limit, $thresholdCallback);

        for ($i = 0; $i < $numberOfEmails; $i++) {
            echo $i;
            $this->sut->addEmailEntityToMarshal(new Entity());
        }

        $this->assertSame($expectedDigests, $this->sut->marshalDigests());
    }

    public function testThatMarshallDigestsCallCallbackFunctionWhenThresholdLimitIsReached()
    {
        $expectedDigests = [
            $this->createEmailDigest(),
        ];

        $digestMarshaller = $this->createMarshaller(true, []);
        $limit = 5;
        $numberOfEmails = $limit + 1;
        $thresholdCallback = function (Entity $emailEntity, int $count) use ($expectedDigests) {
            return $expectedDigests;
        };

        $this->sut = new MaximumThresholdSwitchDigestMarshaller($digestMarshaller, $limit, $thresholdCallback);

        for ($i = 0; $i < $numberOfEmails; $i++) {
            $this->sut->addEmailEntityToMarshal(new Entity());
        }

        $this->assertSame($expectedDigests, $this->sut->marshalDigests());
    }
}
