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
 * @since         3.0.0
 */

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\SkipTablesTruncation;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

class AuthenticationTokenTest extends TestCase
{
    use SkipTablesTruncation;

    public function dataProviderForSessionId()
    {
        return [
            [[AuthenticationToken::SESSION_ID_KEY => 'Foo'], 'Foo',],
            [[AuthenticationToken::SESSION_ID_KEY => ''], '',],
            [[], null,],
        ];
    }

    /**
     * @dataProvider dataProviderForSessionId
     * @param $data
     * @param $expectedSessionId
     */
    public function testAuthenticationToken_GetSessionId($data, $expectedSessionId)
    {
        $entity = AuthenticationTokenFactory::make()
            ->data($data)
            ->getEntity();

        $this->assertEquals($expectedSessionId, $entity->getHashedSessionId());
    }

    /**
     * @dataProvider dataProviderForSessionId
     * @param $data
     */
    public function testAuthenticationToken_hashAndSetSessionId($data)
    {
        $entity = AuthenticationTokenFactory::make()
            ->data($data)
            ->getEntity();

        $newSession = 'Bar';
        $entity->hashAndSetSessionId($newSession);

        $this->assertTrue($entity->checkSessionId($newSession));
    }

    /**
     * @see \App\Model\Entity\AuthenticationToken::checkSessionId()
     */
    public function testAuthenticationToken_hashAndSetSessionId_LongId()
    {
        $accessToken1 = (new JwtTokenCreateService())->createToken(UuidFactory::uuid());
        $accessToken2 = (new JwtTokenCreateService())->createToken(UuidFactory::uuid());
        $entity = AuthenticationTokenFactory::make()->getEntity();
        $entity->hashAndSetSessionId($accessToken1);
        $this->assertTextNotEquals($accessToken1, $accessToken2);
        $this->assertTrue($entity->checkSessionId($accessToken1));
        $this->assertFalse($entity->checkSessionId($accessToken2));
    }

    public function testAuthenticationToken_getJsonDecodedData()
    {
        $data = ['foo' => 'bar'];
        $entity = AuthenticationTokenFactory::make()
            ->data($data)
            ->getEntity();

        $this->assertSame($data, $entity->getJsonDecodedData());

        // Empty data
        $entity = AuthenticationTokenFactory::make()->getEntity();
        $this->assertSame([], $entity->getJsonDecodedData());
    }

    public function testAuthenticationToken_getInvalidJsonData()
    {
        $entity = AuthenticationTokenFactory::make()
            ->patchData(['data' => 'blah'])
            ->getEntity();

        $this->assertSame([], $entity->getJsonDecodedData());
    }
}
