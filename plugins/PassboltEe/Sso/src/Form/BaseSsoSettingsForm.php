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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Form;

use Cake\Form\Form;
use Cake\Validation\Validator;
use Passbolt\Sso\Model\Entity\SsoSetting;

abstract class BaseSsoSettingsForm extends Form
{
    /**
     * Validation of the data
     *
     * @return \Cake\Validation\Validator
     */
    abstract protected function getDataValidator(): Validator;

    /**
     * Build form validation
     *
     * @param \Cake\Validation\Validator $validator validation rules
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('provider', __('A provider is required.'))
            ->notEmptyString('provider', __('The provider should not be empty.'))
            ->maxLength('provider', 64)
            ->inList('provider', SsoSetting::ALLOWED_PROVIDERS, __('The provider is not supported.'));

        $validator
            ->requirePresence('data', __('Data is required.'))
            ->isArray('data', __('Data must be an array of properties.'));

        $validator->addNested('data', $this->getDataValidator());

        return $validator;
    }
}
