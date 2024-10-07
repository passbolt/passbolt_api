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
namespace Passbolt\Metadata\Model\Dto;

class MetadataSettingsDto
{
    /**
     * @var array data
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param array|null $data data
     */
    public function __construct(?array $data = null)
    {
        $this->data = $data ?? [];
    }

    /**
     * @return ?array
     */
    public function toHumanReadableArray(): ?array
    {
        return $this->data;
    }

    /**
     * @return ?array
     */
    public function toArray(): ?array
    {
        return $this->data;
    }

    /**
     * @return string self::data serialized as json string
     * @throws \JsonException if there is an issue with the data
     */
    public function toJson(): string
    {
        return json_encode($this->data, JSON_THROW_ON_ERROR);
    }
}
