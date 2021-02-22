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

namespace Passbolt\EmailDigest\Test\TestCase\Unit\Service;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;

class EmailDigestServiceTest extends AppIntegrationTestCase
{
    use EmailDigestMockTestTrait;

    /**
     * @var MockObject|DigestFactory
     */
    //private $digestFactoryMock;

    /**
     * @var EmailDigestService
     */
    //private $emailDigestService;

    public function setUp()
    {
        //$this->digestFactoryMock = $this->createMock(DigestFactory::class);

        //$this->emailDigestService = new EmailDigestService($this->digestFactoryMock);

        parent::setUp();
    }

    public function testThatEmailsAreGroupedByRecipient()
    {
        $this->markTestIncomplete();
    }
}
