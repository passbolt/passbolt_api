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

namespace Passbolt\Sso\Test\TestCase\Service\SsoAuthenticationTokens;

use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\DeleteSsoStateAuthenticationTokenService;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @see \Passbolt\Sso\Service\SsoAuthenticationTokens\DeleteSsoStateAuthenticationTokenService
 */
class DeleteSsoStateAuthenticationTokenServiceTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Service\SsoAuthenticationTokens\DeleteSsoStateAuthenticationTokenService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DeleteSsoStateAuthenticationTokenService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testDeleteSsoStateAuthenticationTokenService_AllSsoStateRecordsDeleted(): void
    {
        SsoAuthenticationTokenFactory::make(5)
            ->type(SsoState::TYPE_SSO_STATE)
            ->persist();

        $this->service->delete();

        $tokens = SsoAuthenticationTokenFactory::find()
            ->where(['type' => SsoState::TYPE_SSO_STATE])
            ->toArray();
        $this->assertCount(0, $tokens);
    }

    public function testDeleteSsoStateAuthenticationTokenService_OnlySsoStateRecordsDeleted(): void
    {
        SsoAuthenticationTokenFactory::make(5)
            ->type(SsoState::TYPE_SSO_STATE)
            ->persist();
        SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_SET_SETTINGS)->persist();
        SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_GET_KEY)->persist();

        $this->service->delete();

        $tokens = SsoAuthenticationTokenFactory::find()->toArray();
        $this->assertCount(2, $tokens);
    }

    public function testDeleteSsoStateAuthenticationTokenService_NoSsoStateRecords(): void
    {
        SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_GET_KEY)->persist();

        $this->service->delete();

        $this->assertCount(1, SsoAuthenticationTokenFactory::find()->toArray());
    }
}
