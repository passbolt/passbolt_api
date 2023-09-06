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

class PassphraseGeneratorSettingsDto
{
    public const PASSPHRASE_GENERATOR_WORDS_MIN = 4;
    public const PASSPHRASE_GENERATOR_WORDS_MAX = 40;
    public const PASSPHRASE_GENERATOR_WORD_SEPARATOR_LENGTH_MAX = 10;

    /**
     * Passphrase generator words cases
     */
    public const PASSPHRASE_GENERATOR_WORDS_CASE_LOWER = 'lowercase';
    public const PASSPHRASE_GENERATOR_WORDS_CASE_UPPER = 'uppercase';
    public const PASSPHRASE_GENERATOR_WORDS_CASE_CAMEL = 'camelcase';

    /**
     * Allowed passphrase cases.
     */
    public const ALLOWED_PASSPHRASE_GENERATOR_WORDS_CASES = [
        self::PASSPHRASE_GENERATOR_WORDS_CASE_LOWER,
        self::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
        self::PASSPHRASE_GENERATOR_WORDS_CASE_CAMEL,
    ];

    /**
     * @var int|null
     */
    public $words;

    /**
     * @var string|null
     */
    public $word_separator;

    /**
     * @var string|null
     */
    public $word_case;

    /**
     * @param int|string|null $words Number of words to generate.
     * @param string|null $wordSeparator A charactor/string used as a separator for each words.
     * @param string|null $wordCase Word case.
     */
    public function __construct($words, ?string $wordSeparator, ?string $wordCase)
    {
        $this->words = (int)$words;
        $this->word_separator = $wordSeparator;
        $this->word_case = $wordCase;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array|null $data Data.
     * @return self
     */
    public static function createFromArray($data): self
    {
        return new self(
            $data['words'] ?? null,
            $data['word_separator'] ?? null,
            $data['word_case'] ?? null
        );
    }

    /**
     * Returns default settings for passphrase generator.
     *
     * @param array $data The data that override the default
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'words' => 9,
            'word_separator' => ' ',
            'word_case' => self::PASSPHRASE_GENERATOR_WORDS_CASE_LOWER,
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
            'words' => $this->words,
            'word_separator' => $this->word_separator,
            'word_case' => $this->word_case,
        ];
    }
}
