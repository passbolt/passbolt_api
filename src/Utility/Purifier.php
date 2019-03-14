<?php
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
 * @since         2.0.0
 */
namespace App\Utility;

class Purifier
{
    /**
     * Purify a html string
     * @param mixed $html html to clean
     * @return mixed
     */
    public static function clean(string $html = null)
    {
        if (is_null($html)) {
            return null;
        }

        return htmlspecialchars($html, ENT_QUOTES);
    }
}
