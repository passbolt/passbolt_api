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
 * @since         3.5.0
 */

namespace App\Service\Setup;

use Cake\View\ViewBuilder;

interface RecoverStartServiceInterface
{
    /**
     * @param string $userId User uuid
     * @param string $token Register token
     * @return array data to pass to the view
     */
    public function getInfo(string $userId, string $token): array;

    /**
     * @param \Cake\View\ViewBuilder $viewBuilder View builder
     * @return void
     */
    public function setTemplate(ViewBuilder $viewBuilder): void;
}
