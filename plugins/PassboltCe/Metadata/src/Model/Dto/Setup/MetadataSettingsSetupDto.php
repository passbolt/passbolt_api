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
namespace Passbolt\Metadata\Model\Dto\Setup;

class MetadataSettingsSetupDto
{
    private bool $enableForNewInstances;

    public const ENABLE_FOR_NEW_INSTANCES = 'enable_encrypted_metadata_on_install';

    /**
     * @param bool $enableForNewInstances Value to set into DTO.
     */
    public function __construct(bool $enableForNewInstances)
    {
        $this->enableForNewInstances = $enableForNewInstances;
    }

    /**
     * Array representation of DTO.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ENABLE_FOR_NEW_INSTANCES => $this->enableForNewInstances,
        ];
    }
}
