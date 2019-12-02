<?php

namespace App\Test\Lib\Utility\AuthToken;

use App\Model\Entity\AuthenticationToken;
use App\Utility\AuthToken\AuthTokenExpiry;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;

class AuthTokenExpiryTest extends TestCase
{
    /**
     * @var AuthTokenExpiry
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new AuthTokenExpiry();

        parent::setUp();
    }

    public function testThatGetExpirationForInvalidTokenTypeThrowAnException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->sut->getExpirationForTokenType('type');
    }

    public function testThatGetExpirationForTokenRetrieveConfigurationForTokenType()
    {
        $tokenType = AuthenticationToken::TYPE_LOGIN;
        $expectedExpiry = '30 days';
        Configure::clear();
        Configure::write('passbolt.auth.token.' . $tokenType . '.expiry', $expectedExpiry);
        Configure::write('passbolt.auth.tokenExpiry', '10 days');

        $this->assertEquals($expectedExpiry, $this->sut->getExpirationForTokenType($tokenType));
    }

    public function testThatGetExpirationForTokenFallbackToDefaultExpiryConfigurationIfExpiryNotDefinedForTokenType()
    {
        $tokenType = AuthenticationToken::TYPE_LOGIN;
        $expectedExpiry = '30 days';
        Configure::clear();
        Configure::write('passbolt.auth.tokenExpiry', $expectedExpiry);

        $this->assertEquals($expectedExpiry, $this->sut->getExpirationForTokenType($tokenType));
    }

    public function testThatGetExpirationForTokenFallbackToDefaultExpiryConfigurationIfExpiryInvalidForTokenType()
    {
        $tokenType = AuthenticationToken::TYPE_LOGIN;
        $expectedExpiry = '30 days';
        Configure::clear();
        Configure::write('passbolt.auth.token.' . $tokenType . '.expiry', null);
        Configure::write('passbolt.auth.tokenExpiry', $expectedExpiry);

        $this->assertEquals($expectedExpiry, $this->sut->getExpirationForTokenType($tokenType));
    }
}
