<?php
declare(strict_types=1);

/**
 * MIT License
 *
 * Copyright (c) 2022 Passbolt SA (https://www.passbolt.com)
 * Copyright (c) 2016 TheNetw.org
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @link          https://github.com/TheNetworg/oauth2-azure/blob/master/src/Token/AccessToken.php
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.9.0
 */
namespace Passbolt\Sso\Utility\Azure\OpenId;

use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\OpenId\BaseIdToken;

/**
 * @property \Passbolt\Sso\Utility\Azure\Provider\AzureProvider $provider
 */
class AzureIdToken extends BaseIdToken
{
    /**
     * {@inheritDoc}
     *
     * Override this method to perform provider specific assertions.
     */
    public function assertTokenClaims(array $tokenClaims): void
    {
        parent::assertTokenClaims($tokenClaims);

        if (
            !isset($tokenClaims['tid']) || !is_string($tokenClaims['tid']) ||
            $this->provider->getTenant() != $tokenClaims['tid']
        ) {
            throw new BadRequestException('The tid (tenant id) parameter is invalid.');
        }

        if (
            !isset($tokenClaims['ver']) || !is_string($tokenClaims['ver']) ||
            $tokenClaims['ver'] != AzureProvider::ENDPOINT_VERSION_2_0
        ) {
            throw new BadRequestException('The ver (version) parameter is invalid.');
        }
    }
}
