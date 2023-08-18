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
namespace Passbolt\PasswordPolicies\Validation;

use Cake\Validation\Validator;

class PassphraseGeneratorSettingsValidator extends Validator
{
    /**
     * The passphrase generator min words length
     *
     * @var int
     */
    public const PASSPHRASE_GENERATOR_WORDS_MIN = 4;

    /**
     * The passphrase generator max words length
     *
     * @var int
     */
    public const PASSPHRASE_GENERATOR_WORDS_MAX = 40;

    /**
     * The passphrase generator word separator max length
     *
     * @var int
     */
    public const PASSPHRASE_GENERATOR_WORD_SEPARATOR_LENGTH_MAX = 10;

    /**
     * The passphrase generator word case lower
     *
     * @var string
     */
    public const PASSPHRASE_GENERATOR_WORDS_CASE_LOWER = 'lowercase';

    /**
     * The passphrase generator word case upper
     *
     * @var string
     */
    public const PASSPHRASE_GENERATOR_WORDS_CASE_UPPER = 'uppercase';

    /**
     * The passphrase generator word case camelcase
     *
     * @var string
     */
    public const PASSPHRASE_GENERATOR_WORDS_CASE_CAMEL = 'camelcase';

    /**
     * Allowed passphrase cases.
     */
    public const PASSPHRASE_GENERATOR_WORDS_CASES = [
        self::PASSPHRASE_GENERATOR_WORDS_CASE_LOWER,
        self::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
        self::PASSPHRASE_GENERATOR_WORDS_CASE_CAMEL,
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this
            ->requirePresence(
                'words',
                true,
                __('The passphrase generator words is required.')
            )
            ->range(
                'words',
                [
                    self::PASSPHRASE_GENERATOR_WORDS_MIN,
                    self::PASSPHRASE_GENERATOR_WORDS_MAX,
                ],
                __(
                    'The passphrase generator words should be between {0} and {1}.',
                    self::PASSPHRASE_GENERATOR_WORDS_MIN,
                    self::PASSPHRASE_GENERATOR_WORDS_MAX
                )
            );

        $this
            ->requirePresence(
                'word_separator',
                true,
                __('The passphrase generator word separator is required.')
            )
            ->allowEmptyString('word_separator')
            ->utf8Extended(
                'word_separator',
                __('The passphrase generator word separator should be a valid UTF8 string.')
            )
            ->maxLength(
                'word_separator',
                self::PASSPHRASE_GENERATOR_WORD_SEPARATOR_LENGTH_MAX,
                __(
                    'The passphrase generator word separator should be maximum {0} characters.',
                    self::PASSPHRASE_GENERATOR_WORD_SEPARATOR_LENGTH_MAX
                )
            );

        $this
            ->requirePresence(
                'word_case',
                true,
                __('The passphrase generator word case is required.')
            )
            ->inList(
                'word_case',
                self::PASSPHRASE_GENERATOR_WORDS_CASES,
                __(
                    'The passphrase generator word case should be one of the following: {0}.',
                    implode(', ', self::PASSPHRASE_GENERATOR_WORDS_CASES)
                )
            );
    }
}
