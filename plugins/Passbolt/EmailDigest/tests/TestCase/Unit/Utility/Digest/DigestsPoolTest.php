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

namespace Passbolt\EmailDigest\Test\TestCase\Unit\Utility\Digest;

use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;

class DigestsPoolTest extends TestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var DigestsPool
     */
    private $digestsPool;

    public function setUp()
    {
        $this->digestsPool = DigestsPool::getInstance();
        parent::setUp();
    }

    public function testEmailDigestDigestsPoolAddDigest()
    {
        // Create a lambda digest
        $digest = $this->createDigest(true, []);

        $this->digestsPool->addDigest($digest);

        // Assert that digest was added with success
        $this->assertContains($digest, $this->digestsPool->getDigests());
    }

    public function testEmailDigestDigestsPoolGetDigest()
    {
        $digestsPool = DigestsPool::getInstance();

        $digestWithHighestPriority = $this->createDigest(true, []);
        $digestWithLowestPriority = $this->createDigest(true, []);

        $this->digestsPool->addDigest($digestWithHighestPriority, 2);
        $this->digestsPool->addDigest($digestWithLowestPriority, 1);

        $digests = $digestsPool->getDigests();

        $this->assertSame($digestWithHighestPriority, $digests[0]);
        $this->assertSame($digestWithLowestPriority, $digests[1]);
    }
}
