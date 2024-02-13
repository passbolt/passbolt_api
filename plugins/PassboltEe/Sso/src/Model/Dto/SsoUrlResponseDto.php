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
 * @since         4.4.0
 */

namespace Passbolt\Sso\Model\Dto;

use InvalidArgumentException;
use Passbolt\Sso\Utility\UrlParser;

class SsoUrlResponseDto
{
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';

    /**
     * @var string url with parameters such as nonce, state, etc.
     */
    private $url;

    /**
     * @var string http method
     */
    private $method;

    /**
     * @param string $url SSO URL.
     * @param string|null $method GET|POST
     */
    public function __construct(string $url, ?string $method = null)
    {
        $this->url = $url;
        $this->method = $method ?? self::HTTP_GET;
        if ($this->method !== self::HTTP_GET && $this->method !== self::HTTP_POST) {
            throw new InvalidArgumentException('This SSO Url Method is not supported');
        }
    }

    /**
     * @return void
     */
    public function setPostMethod(): void
    {
        $this->method = self::HTTP_POST;
    }

    /**
     * @return string url
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return mixed string|array to be serialized
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        if ($this->method === self::HTTP_GET) {
            return [
                'url' => $this->url,
            ];
        }

        return [
            'method' => $this->method,
            'url' => UrlParser::getUrlOnly($this->url),
            'data' => UrlParser::parseQueryString($this->url),
        ];
    }
}
