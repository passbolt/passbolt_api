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
 * @since         4.0.0
 */
namespace Passbolt\Sso\Form;

use Cake\Validation\Validator;

class SsoSettingsGoogleDataForm extends BaseSsoSettingsForm
{
    /**
     * @inheritDoc
     */
    protected function getDataValidator(): Validator
    {
        $validator = new Validator();

        $validator
            ->requirePresence('client_id', __('A client id is required.'))
            ->notEmptyString('client_id', __('The client id should not be empty.'))
            ->maxLength('client_id', 256, __('The client id is too large.'));

        $validator
            ->requirePresence('client_secret', __('A client secret is required.'))
            ->notEmptyString('client_secret', __('The client secret should not be empty.'))
            ->ascii('client_secret', __('The client secret should be a valid string.'))
            ->maxLength('client_secret', 256, __('The client secret is too large.'));

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
