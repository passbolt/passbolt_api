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
 * @since         5.4.0
 */
namespace Passbolt\Metadata\Model\Dto;

class MetadataSettingsGetStartedDto
{
    private bool $enableForExistingInstances;

    public const ENABLE_FOR_EXISTING_INSTANCES = 'enabled';

    /**
     * @param bool $enableForExistingInstances Value to set into DTO.
     */
    public function __construct(bool $enableForExistingInstances)
    {
        $this->enableForExistingInstances = $enableForExistingInstances;
    }

    /**
     * Array representation of DTO.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ENABLE_FOR_EXISTING_INSTANCES => $this->enableForExistingInstances,
        ];
    }
}
