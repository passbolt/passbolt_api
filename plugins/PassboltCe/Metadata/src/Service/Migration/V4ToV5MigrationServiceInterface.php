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
namespace Passbolt\Metadata\Service\Migration;

interface V4ToV5MigrationServiceInterface
{
    /**
     * Migrate V4 items to V5.
     *
     * @return array Result.
     * @throws \Cake\Http\Exception\BadRequestException If V5 item creation/modification is not allowed.
     */
    public function migrate(): array;

    /**
     * Returns human-readable name (in singular form).
     *
     * @return string
     */
    public function getHumanReadableName(): string;
}