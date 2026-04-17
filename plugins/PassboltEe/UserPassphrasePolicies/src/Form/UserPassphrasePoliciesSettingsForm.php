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
 * @since         4.3.0
 */
namespace Passbolt\UserPassphrasePolicies\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class UserPassphrasePoliciesSettingsForm extends Form
{
    public const ENTROPY_MINIMUM_VALUE_MIN = 50;
    public const ENTROPY_MINIMUM_VALUE_MAX = 224;

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('entropy_minimum', 'integer')
            ->addField('external_dictionary_check', 'boolean');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(
                'entropy_minimum',
                true,
                __('The entropy minimum is required.')
            )
            ->range(
                'entropy_minimum',
                [self::ENTROPY_MINIMUM_VALUE_MIN, self::ENTROPY_MINIMUM_VALUE_MAX],
                __(
                    'The entropy minimum should be between {0} and {1}.',
                    (string)self::ENTROPY_MINIMUM_VALUE_MIN,
                    (string)self::ENTROPY_MINIMUM_VALUE_MAX
                )
            );

        $validator
            ->requirePresence(
                'external_dictionary_check',
                true,
                __('The external dictionary check is required.')
            )
            ->boolean(
                'external_dictionary_check',
                __('The external dictionary check should be a boolean.')
            );

        return $validator;
    }
}
