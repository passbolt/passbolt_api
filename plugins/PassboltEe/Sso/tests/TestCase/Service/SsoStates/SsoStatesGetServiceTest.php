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
 * @since         3.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\SsoStates;

use App\Test\Factory\UserFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoStates\SsoStatesGetService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @see \Passbolt\Sso\Service\SsoStates\SsoStatesGetService
 */
class SsoStatesGetServiceTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Service\SsoStates\SsoStatesGetService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoStatesGetService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSsoStatesGetService_ErrorRecordNotFound(): void
    {
        $this->expectException(RecordNotFoundException::class);
        $this->expectErrorMessage('The SSO state does not exist.');

        $this->service->getOrFail(SsoState::generate());
    }

    public function testSsoStatesGetService_ErrorRecordDeleted(): void
    {
        $userId = UserFactory::make()->admin()->persist()->get('id');
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make(['type' => SsoState::TYPE_SSO_SET_SETTINGS])
            ->ssoSettingsId($ssoSettingId)
            ->userId($userId)
            ->deleted()
            ->persist();

        $this->expectException(RecordNotFoundException::class);
        $this->expectErrorMessage('The SSO state does not exist.');

        $this->service->getOrFail($ssoState->state);
    }

    public function testSsoStatesGetService_ErrorInvalidState(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('The SSO state is invalid.');

        $this->service->getOrFail('123456');
    }

    public function testSsoStatesGetService_Success(): void
    {
        $userId = UserFactory::make()->admin()->persist()->get('id');
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make(['type' => SsoState::TYPE_SSO_GET_KEY])
            ->ssoSettingsId($ssoSettingId)
            ->userId($userId)
            ->persist();

        $result = $this->service->getOrFail($ssoState->state);

        $this->assertInstanceOf(SsoState::class, $result);
        $this->assertEquals($ssoState->id, $result->id);
        $this->assertEquals($ssoState->state, $result->state);
        $this->assertEquals(SsoState::TYPE_SSO_GET_KEY, $result->type);
        $this->assertEquals($ssoState->sso_settings_id, $result->sso_settings_id);
        $this->assertEquals($ssoState->user_id, $result->user_id);
        $this->assertEquals($ssoState->ip, $result->ip);
        $this->assertEquals($ssoState->user_agent, $result->user_agent);
        $this->assertTrue($result->deleted->isFuture());
    }
}
