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

class MetadataKeysSettingsDto extends MetadataSettingsDto
{
    public const ALLOW_USAGE_OF_PERSONAL_KEYS = 'allow_usage_of_personal_keys';
    public const ZERO_KNOWLEDGE_KEY_SHARE = 'zero_knowledge_key_share';

    public const PROPS = [
        self::ALLOW_USAGE_OF_PERSONAL_KEYS,
        self::ZERO_KNOWLEDGE_KEY_SHARE,
    ];

    /**
     * @var array data
     */
    protected array $data = [];

    /**
     * Constructor.
     *
     * @param array|null $data data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        $this->data = [
            self::ALLOW_USAGE_OF_PERSONAL_KEYS => $data[self::ALLOW_USAGE_OF_PERSONAL_KEYS] ?? null,
            self::ZERO_KNOWLEDGE_KEY_SHARE => $data[self::ZERO_KNOWLEDGE_KEY_SHARE] ?? null,
        ];
    }

    /**
     * @return bool
     */
    public function isUsageOfPersonalKeysAllowed(): bool
    {
        return isset($this->data[self::ALLOW_USAGE_OF_PERSONAL_KEYS])
            && $this->data[self::ALLOW_USAGE_OF_PERSONAL_KEYS];
    }

    /**
     * @return bool
     */
    public function isKeyShareZeroKnowledge(): bool
    {
        return isset($this->data[self::ZERO_KNOWLEDGE_KEY_SHARE])
            && $this->data[self::ZERO_KNOWLEDGE_KEY_SHARE];
    }

    /**
     * @return ?array
     */
    public function toHumanReadableArray(): ?array
    {
        return [
            __('Allow usage of personal keys') => $this->data[self::ALLOW_USAGE_OF_PERSONAL_KEYS] ?
                __('True') : __('False'),
            __('Zero-knowledge key share') => $this->data[self::ZERO_KNOWLEDGE_KEY_SHARE] ?
                __('True') : __('False'),
        ];
    }
}
