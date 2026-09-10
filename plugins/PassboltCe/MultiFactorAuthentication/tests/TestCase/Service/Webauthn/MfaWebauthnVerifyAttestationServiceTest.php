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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService
 */
class MfaWebauthnVerifyAttestationServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnVerifyAttestationService $service;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        $policy = new DefaultMfaWebauthnPolicyService();
        $this->service = new MfaWebauthnVerifyAttestationService(
            new MfaWebauthnRelyingPartyService($policy),
            new MfaWebauthnEnableService($policy),
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_UnknownHandle_Throws(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $this->expectException(NotFoundException::class);
        $this->service->verify($uac, UuidFactory::uuid(), ['not' => 'a real credential']);
    }

    public function testMfaWebauthnVerifyAttestationService_Verify_MalformedCredentialPayload_Throws(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $token = (new MfaWebauthnChallengeTokenService())->create(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            '{"challenge":"abc"}',
        );

        $this->expectException(BadRequestException::class);
        $this->service->verify($uac, $token->token, ['missing' => 'required fields']);
    }
}
