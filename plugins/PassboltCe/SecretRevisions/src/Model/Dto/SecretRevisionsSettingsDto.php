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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Model\Dto;

use App\Model\Dto\RequestDtoInterface;
use Cake\Utility\Hash;

class SecretRevisionsSettingsDto implements RequestDtoInterface
{
    private int $maxRevisions;

    private bool $allowSharingRevisions;

    /**
     * Constructor.
     *
     * @param int $maxRevisions Max revision value.
     * @param bool $allowSharingRevisions Allow sharing revisions value.
     */
    final public function __construct(int $maxRevisions, bool $allowSharingRevisions)
    {
        $this->maxRevisions = $maxRevisions;
        $this->allowSharingRevisions = $allowSharingRevisions;
    }

    /**
     * @param array $data Data to create DTO from.
     * @return static
     */
    public static function createFromArray(array $data): static
    {
        $maxRevisions = (int)Hash::get($data, 'max_revisions');
        $allowSharingRevisions = (bool)Hash::get($data, 'allow_sharing_revisions');

        return new static($maxRevisions, $allowSharingRevisions);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'max_revisions' => $this->maxRevisions,
            'allow_sharing_revisions' => $this->allowSharingRevisions,
        ];
    }

    /**
     * @return int
     */
    public function getMaxRevisions(): int
    {
        return $this->maxRevisions;
    }

    /**
     * @return string self::data serialized as json string
     * @throws \JsonException if there is an issue with the data
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
