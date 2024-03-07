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
 * @since         4.6.0
 */
namespace Passbolt\Sso\Form;

use Cake\Validation\Validator;
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsAdfsDataForm extends SsoSettingsOAuth2DataForm
{
    /**
     * Supported email claims.
     */
    public const SUPPORTED_EMAIL_CLAIM = [SsoSetting::ADFS_EMAIL_CLAIM_UPN];

    /**
     * @inheritDoc
     */
    protected function getDataValidator(): Validator
    {
        $dataValidator = parent::getDataValidator();

        $dataValidator
            ->notEmptyString('email_claim', __('The email claim should not be empty.'))
            ->maxLength('email_claim', 64, __('The email claim is too large.'))
            ->inList(
                'email_claim',
                self::SUPPORTED_EMAIL_CLAIM,
                __(
                    'The email claim should be one of the following: {0}.',
                    implode(', ', self::SUPPORTED_EMAIL_CLAIM)
                )
            );

        return $dataValidator;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
