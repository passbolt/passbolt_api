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
namespace Passbolt\MfaPolicies\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;

class MfaPoliciesSettingsForm extends Form
{
    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('policy', 'string')
            ->addField('remember_me_for_a_month', 'boolean');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('policy', true, __('The policy field is required.'))
            ->inList('policy', MfaPoliciesSetting::ALLOWED_POLICIES, __(
                'The type of the policy should be one of the following: {0}.',
                implode(', ', MfaPoliciesSetting::ALLOWED_POLICIES)
            ));

        $validator
            ->requirePresence(
                'remember_me_for_a_month',
                true,
                __('The remember me for a month field is required.')
            )
            ->boolean(
                'remember_me_for_a_month',
                __('The remember me for a month should be a boolean type.')
            );

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
