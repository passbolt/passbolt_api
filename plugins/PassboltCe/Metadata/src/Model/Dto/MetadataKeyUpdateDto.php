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

use Cake\I18n\FrozenTime;

class MetadataKeyUpdateDto
{
    public string $fingerprint;

    public string $armoredKey;

    public FrozenTime $expired;

    /**
     * Constructor.
     *
     * @param string $fingerprint Fingerprint.
     * @param string $armoredKey Armored key.
     * @param \Cake\I18n\FrozenTime $expired Expired time
     */
    public function __construct(string $fingerprint, string $armoredKey, FrozenTime $expired)
    {
        $this->fingerprint = $fingerprint;
        $this->armoredKey = $armoredKey;
        $this->expired = $expired;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'fingerprint' => $this->fingerprint,
            'armored_key' => $this->armoredKey,
            'expired' => $this->expired,
        ];
    }

    /**
     * @param array $data Data to transform into DTO.
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self($data['fingerprint'], $data['armored_key'], $data['expired']);
    }
}
