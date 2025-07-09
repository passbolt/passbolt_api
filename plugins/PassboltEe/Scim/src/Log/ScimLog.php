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
 * @since         4.10.0
 */
namespace Passbolt\Scim\Log;

use Cake\Log\Log;
use Stringable;

/**
 * Scim log
 */
class ScimLog extends Log
{
    /**
     * @inheritDoc
     */
    public static function write(string|int $level, Stringable|string $message, array|string $context = []): bool
    {
        $context['scope'] = ['scim'];

        return parent::write($level, $message, $context);
    }
}
