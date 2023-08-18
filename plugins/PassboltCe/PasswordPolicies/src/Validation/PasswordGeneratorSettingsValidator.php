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

class PasswordGeneratorSettingsValidator extends Validator
{
    /**
     * Password generator setting min length
     *
     * @var int
     */
    public const PASSWORD_GENERATOR_SETTING_LENGTH_MIN = 8;

    /**
     * Password generator setting max length
     *
     * @var int
     */
    public const PASSWORD_GENERATOR_SETTING_LENGTH_MAX = 128;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this
            ->range(
                'length',
                [
                    self::PASSWORD_GENERATOR_SETTING_LENGTH_MIN,
                    self::PASSWORD_GENERATOR_SETTING_LENGTH_MAX,
                ],
                __(
                    'The password generator length should be between {0} and {1}.',
                    self::PASSWORD_GENERATOR_SETTING_LENGTH_MIN,
                    self::PASSWORD_GENERATOR_SETTING_LENGTH_MAX,
                )
            )
            ->requirePresence(
                'length',
                true,
                __('The password generator length is required.')
            );

        $this
            ->requirePresence(
                'mask_upper',
                true,
                __('The password generator mask upper is required.')
            )
            ->boolean('mask_upper', __('The password generator mask upper should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_lower',
                true,
                __('The password generator mask lower is required.')
            )
            ->boolean('mask_lower', __('The password generator mask lower should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_digit',
                true,
                __('The password generator mask digit is required.')
            )
            ->boolean('mask_digit', __('The password generator mask digit should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_parenthesis',
                true,
                __('The password generator mask parenthesis is required.')
            )
            ->boolean('mask_parenthesis', __('The password generator mask parenthesis should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_emoji',
                true,
                __('The password generator mask emoji is required.')
            )
            ->boolean('mask_emoji', __('The password generator mask emoji should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_char1',
                true,
                __('The password generator mask char1 is required.')
            )
            ->boolean('mask_char1', __('The password generator mask char1 should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_char2',
                true,
                __('The password generator mask char2 is required.')
            )
            ->boolean('mask_char2', __('The password generator mask char2 should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_char3',
                true,
                __('The password generator mask char3 is required.')
            )
            ->boolean('mask_char3', __('The password generator mask char3 should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_char4',
                true,
                __('The password generator mask char4 is required.')
            )
            ->boolean('mask_char4', __('The password generator mask char4 should be a boolean type.'));

        $this
            ->requirePresence(
                'mask_char5',
                true,
                __('The password generator mask char5 is required.')
            )
            ->boolean('mask_char5', __('The password generator mask char5 should be a boolean type.'));

        $this
            ->requirePresence(
                'exclude_look_alike_chars',
                true,
                __('The password generator exclude look alike chars is required.')
            )
            ->boolean(
                'exclude_look_alike_chars',
                __('The password generator exclude look alike chars should be a boolean type.')
            );
    }

    /**
     * Custom validation rule to make sure at lease one mask field is selected.
     *
     * @param array|string $value Value to check.
     * @return bool
     */
    public static function checkAtLeastOneMaskIsSelected($value): bool
    {
        $fields = [
            'mask_upper',
            'mask_lower',
            'mask_digit',
            'mask_parenthesis',
            'mask_emoji',
            'mask_char1',
            'mask_char2',
            'mask_char3',
            'mask_char4',
            'mask_char5',
        ];

        foreach ($fields as $field) {
            if (isset($value[$field]) && $value[$field]) {
                return true;
            }
        }

        return false;
    }
}
