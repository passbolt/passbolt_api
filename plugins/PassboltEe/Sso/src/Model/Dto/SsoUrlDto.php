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
 * @since         4.1.0
 */

namespace Passbolt\Sso\Model\Dto;

use JsonSerializable;
use Passbolt\Sso\Utility\UrlParser;

class SsoUrlDto implements JsonSerializable
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @param string $url SSO URL.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return void
     */
    public function setPostMethod(): void
    {
        $this->method = 'POST';
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'method' => $this->method,
            'url' => UrlParser::getUrlOnly($this->url),
            'data' => UrlParser::parseQueryString($this->url),
        ];
    }
}
