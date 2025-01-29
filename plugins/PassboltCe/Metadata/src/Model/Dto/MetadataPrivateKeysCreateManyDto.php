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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Model\Dto;

use Cake\Http\Exception\BadRequestException;

class MetadataPrivateKeysCreateManyDto
{
    /**
     * @var array
     */
    private array $data = [];

    /**
     * @param array $requestData Request data to convert into DTO.
     * @return void
     */
    public function __construct(array $requestData)
    {
        foreach ($requestData as $data) {
            if (!is_array($data)) {
                throw new BadRequestException(__('The metadata private key data must be an array.'));
            }

            $this->data[] = [
                'metadata_key_id' => $data['metadata_key_id'] ?? null,
                'user_id' => $data['user_id'] ?? null,
                'data' => $data['data'] ?? null,
            ];
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
