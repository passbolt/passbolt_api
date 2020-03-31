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

namespace Passbolt\EmailDigest\Test\TestCase\Unit\Utility\Marshaller;

use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;

class DigestMarshallerPoolTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var DigestMarshallerPool
     */
    private $sut;

    public function setUp()
    {
        $this->sut = DigestMarshallerPool::getInstance();
        parent::setUp();
    }

    public function testAddDigestMarshaller()
    {
        // Create a lambda marshaller
        $marshaller = $this->createMarshaller(true, []);

        $this->sut->addDigestMarshaller($marshaller);

        // Assert that marshaller was added with success
        $this->assertContains($marshaller, $this->sut->getDigestMarshallers());
    }

    public function testGetDigestMarshallerRetrieveDigestMarshallerByPriority()
    {
        $sut = DigestMarshallerPool::getInstance();

        $marshallerWithHighestPriority = $this->createMarshaller(true, []);
        $marshallerWithLowestPriority = $this->createMarshaller(true, []);

        $this->sut->addDigestMarshaller($marshallerWithHighestPriority, 2); // marshaller has the highest priority
        $this->sut->addDigestMarshaller($marshallerWithLowestPriority, 1);

        $marshallers = $sut->getDigestMarshallers();

        $this->assertSame($marshallerWithHighestPriority, $marshallers[0]);
        $this->assertSame($marshallerWithLowestPriority, $marshallers[1]);
    }
}
