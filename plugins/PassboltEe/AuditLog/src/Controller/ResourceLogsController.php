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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\AuditLog\Utility\ResourceActionLogsFinder;

class ResourceLogsController extends BaseLogsController
{
    /**
     * @inheritDoc
     */
    public function getModelName(): string
    {
        return 'Resources';
    }

    /**
     * View action logs for a given resource.
     *
     * @param string|null $resourceId resource id
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource id has the wrong format
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot access the given resource, or if the resource does not exist
     */
    public function view(?string $resourceId = null)
    {
        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $this->viewByEntity(new ResourceActionLogsFinder(), $resourceId);
    }
}
