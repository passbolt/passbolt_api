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
 * @since         4.2.0
 */
namespace Passbolt\PasswordPolicies\Model\Dto;

class PasswordGeneratorSettingsDto
{
    /**
     * @var int|null
     */
    public $length;

    /**
     * @var bool|null
     */
    public $mask_upper;

    /**
     * @var bool|null
     */
    public $mask_lower;

    /**
     * @var bool|null
     */
    public $mask_digit;

    /**
     * @var bool|null
     */
    public $mask_parenthesis;

    /**
     * @var bool|null
     */
    public $mask_emoji;

    /**
     * @var bool|null
     */
    public $mask_char1;

    /**
     * @var bool|null
     */
    public $mask_char2;

    /**
     * @var bool|null
     */
    public $mask_char3;

    /**
     * @var bool|null
     */
    public $mask_char4;

    /**
     * @var bool|null
     */
    public $mask_char5;

    /**
     * @var bool|null
     */
    public $exclude_look_alike_chars;

    /**
     * @param int|string|null $length Length of password that will be generated.
     * @param bool|string|null $maskUpper Mask upper flag.
     * @param bool|string|null $maskLower Mask lower flag.
     * @param bool|string|null $maskDigit Mask digit flag.
     * @param bool|string|null $maskParenthesis Mask parenthesis flag.
     * @param bool|string|null $maskEmoji Mask emoji flag.
     * @param bool|string|null $maskChar1 Mask char1 flag.
     * @param bool|string|null $maskChar2 Mask char2 flag.
     * @param bool|string|null $maskChar3 Mask char3 flag.
     * @param bool|string|null $maskChar4 Mask char4 flag.
     * @param bool|string|null $maskChar5 Mask char5 flag.
     * @param bool|string|null $excludeLookAlikeChars Exclude look alike characters flag.
     */
    public function __construct(
        $length,
        $maskUpper,
        $maskLower,
        $maskDigit,
        $maskParenthesis,
        $maskEmoji,
        $maskChar1,
        $maskChar2,
        $maskChar3,
        $maskChar4,
        $maskChar5,
        $excludeLookAlikeChars
    ) {
        $this->length = (int)$length;
        $this->mask_upper = (bool)$maskUpper;
        $this->mask_lower = (bool)$maskLower;
        $this->mask_digit = (bool)$maskDigit;
        $this->mask_parenthesis = (bool)$maskParenthesis;
        $this->mask_emoji = (bool)$maskEmoji;
        $this->mask_char1 = (bool)$maskChar1;
        $this->mask_char2 = (bool)$maskChar2;
        $this->mask_char3 = (bool)$maskChar3;
        $this->mask_char4 = (bool)$maskChar4;
        $this->mask_char5 = (bool)$maskChar5;
        $this->exclude_look_alike_chars = (bool)$excludeLookAlikeChars;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data = []): self
    {
        return new self(
            $data['length'] ?? null,
            $data['mask_upper'] ?? null,
            $data['mask_lower'] ?? null,
            $data['mask_digit'] ?? null,
            $data['mask_parenthesis'] ?? null,
            $data['mask_emoji'] ?? null,
            $data['mask_char1'] ?? null,
            $data['mask_char2'] ?? null,
            $data['mask_char3'] ?? null,
            $data['mask_char4'] ?? null,
            $data['mask_char5'] ?? null,
            $data['exclude_look_alike_chars'] ?? null,
        );
    }

    /**
     * Create DTO from default.
     *
     * @param array $data The data that override the default
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'length' => 18,
            'mask_upper' => true,
            'mask_lower' => true,
            'mask_digit' => true,
            'mask_parenthesis' => true,
            'mask_emoji' => false,
            'mask_char1' => true,
            'mask_char2' => true,
            'mask_char3' => true,
            'mask_char4' => true,
            'mask_char5' => true,
            'exclude_look_alike_chars' => true,
        ], $data));
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'mask_upper' => $this->mask_upper,
            'mask_lower' => $this->mask_lower,
            'mask_digit' => $this->mask_digit,
            'mask_parenthesis' => $this->mask_parenthesis,
            'mask_emoji' => $this->mask_emoji,
            'mask_char1' => $this->mask_char1,
            'mask_char2' => $this->mask_char2,
            'mask_char3' => $this->mask_char3,
            'mask_char4' => $this->mask_char4,
            'mask_char5' => $this->mask_char5,
            'exclude_look_alike_chars' => $this->exclude_look_alike_chars,
        ];
    }
}
