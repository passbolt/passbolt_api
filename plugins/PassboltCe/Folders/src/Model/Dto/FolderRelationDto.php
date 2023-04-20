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
 * @since         3.10.0
 */
namespace Passbolt\Folders\Model\Dto;

use Cake\Utility\Hash;

class FolderRelationDto
{
    /**
     * @var string|null
     */
    public $foreignModel;

    /**
     * @var string|null
     */
    public $foreignId;

    /**
     * @var string|null
     */
    public $folderParentId;

    /**
     * @var string|null
     */
    public $userId;

    /**
     * Constructor.
     *
     * @param string|null $foreignModel The folder relation foreign model
     * @param string|null $foreignId The folder relation foreign id
     * @param string|null $folderParentId The folder parent id
     * @param string|null $userId The user id
     */
    public function __construct(
        ?string $foreignModel,
        ?string $foreignId,
        ?string $folderParentId = null,
        ?string $userId = null
    ) {
        $this->foreignModel = $foreignModel;
        $this->foreignId = $foreignId;
        $this->folderParentId = $folderParentId;
        $this->userId = $userId;
    }

    /**
     * Clone the object.
     *
     * @return self
     */
    public function clone(): self
    {
        return clone $this;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data): self
    {
        return new self(
            Hash::get($data, 'foreign_model'),
            Hash::get($data, 'foreign_id'),
            Hash::get($data, 'folder_parent_id'),
            Hash::get($data, 'user_id')
        );
    }

    /**
     * Transform into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'foreign_model' => $this->foreignModel,
            'foreign_id' => $this->foreignId,
            'folder_parent_id' => $this->folderParentId,
            'user_id' => $this->userId,
        ];
    }
}
