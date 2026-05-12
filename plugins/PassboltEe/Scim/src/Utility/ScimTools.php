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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility;

use Cake\I18n\DateTime;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Utility class
 */
class ScimTools
{
    public const API_FORMAT_DATETIME = 'Y-m-d\TH:i:s.v\Z';

    /**
     * @param \Cake\I18n\DateTime $dateTime
     * @return string
     */
    public static function formatDateTimeToScim(DateTime $dateTime): string
    {
        return $dateTime->format(self::API_FORMAT_DATETIME);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     */
    public static function isScimApiRequest(ServerRequestInterface $request): bool
    {
        /** @var \Cake\Http\ServerRequest $request */

        return $request->getParam('plugin') === 'Passbolt/Scim' &&
            strtolower((string)$request->getParam('prefix')) === 'v2';
    }
}
