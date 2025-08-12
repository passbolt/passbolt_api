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
 * @since         5.2.0
 */
namespace Passbolt\UserKeyPolicies\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;

class UserKeyPoliciesSettingsForm extends Form
{
    /**
     * The rsa key type
     *
     * @type string
     */
    public const KEY_TYPE_RSA = 'rsa';

    /**
     * The curve key type
     *
     * @type string
     */
    public const KEY_TYPE_CURVE = 'curve';

    /**
     * The allowed key types.
     *
     * @type array
     */
    public const ALLOWED_KEY_TYPES = [self::KEY_TYPE_RSA, self::KEY_TYPE_CURVE];

    /**
     * Allowed key sizes for RSA.
     *
     * @var array
     */
    public const ALLOWED_KEY_SIZES = [
        UserKeyPoliciesSettingsDto::KEY_SIZE_3072,
        UserKeyPoliciesSettingsDto::KEY_SIZE_4096,
    ];

    /**
     * @var array
     */
    public const ALLOWED_KEY_CURVES = [UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY];

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('preferred_key_type', 'string')
            ->addField('preferred_key_size', 'integer')
            ->addField('preferred_key_curve', 'string');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(
                'preferred_key_type',
                true,
                __('The preferred key type is required.')
            )
            ->notEmptyString(
                'preferred_key_type',
                __('The preferred key type should not be empty.')
            )
            ->inList('preferred_key_type', self::ALLOWED_KEY_TYPES, __(
                'The preferred key type should be one of the following: {0}.',
                implode(', ', self::ALLOWED_KEY_TYPES)
            ))
            // Note: Putting these validation in nullable fields doesn't work when value of that field is passed as `null`, hence rule is applied here.
            ->add('preferred_key_type', 'invalid_key_type_size_combination', [
                'rule' => [$this, 'isKeyTypeSizeCombinationValid'],
                'message' => __('The preferred key type and size combination does not match.'),
            ])
            ->add('preferred_key_type', 'invalid_key_type_curve_combination', [
                'rule' => [$this, 'isKeyTypeCurveCombinationValid'],
                'message' => __('The preferred key type and curve combination does not match.'),
            ]);

        $validator
            ->setStopOnFailure()
            ->requirePresence(
                'preferred_key_size',
                true,
                __('The preferred key size is required.')
            )
            ->allowEmptyFor('preferred_key_size', Validator::EMPTY_ALL)
            ->inList(
                'preferred_key_size',
                self::ALLOWED_KEY_SIZES,
                __(
                    'The preferred key size should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_KEY_SIZES)
                ),
                function ($context) {
                    return $context['data']['preferred_key_size'] !== null;
                }
            );

        $validator
            ->setStopOnFailure()
            ->requirePresence(
                'preferred_key_curve',
                true,
                __('The preferred key curve is required.')
            )
            ->allowEmptyFor('preferred_key_curve', Validator::EMPTY_STRING)
            ->inList(
                'preferred_key_curve',
                self::ALLOWED_KEY_CURVES,
                __(
                    'The preferred key curve should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_KEY_CURVES)
                ),
                function ($context) {
                    return $context['data']['preferred_key_curve'] !== null;
                }
            );

        return $validator;
    }

    /**
     * @param mixed $value Value to check.
     * @param array $context Context data.
     * @return bool
     */
    public function isKeyTypeSizeCombinationValid(mixed $value, array $context): bool
    {
        $keySize = $context['data']['preferred_key_size'] ?? null;

        if ($value === UserKeyPoliciesSettingsDto::KEY_TYPE_RSA && $keySize === null) { // RSA can't have null key size
            return false;
        } elseif ($value === UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE && $keySize !== null) { // Curve can't have size
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value Value to check.
     * @param array $context Context data.
     * @return bool
     */
    public function isKeyTypeCurveCombinationValid(mixed $value, array $context): bool
    {
        $keyCurve = $context['data']['preferred_key_curve'] ?? null;

        if ($value === UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE && $keyCurve === null) { // Curve value must be present for curve type
            return false;
        } elseif ($value === UserKeyPoliciesSettingsDto::KEY_TYPE_RSA && $keyCurve !== null) { // RSA can't have elliptic-curve setting
            return false;
        }

        return true;
    }
}
