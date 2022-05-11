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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Form;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Form\Form;

class MfaForm extends Form implements MfaFormInterface
{
    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * TotpSettingsForm constructor.
     *
     * @param \App\Utility\UserAccessControl $uac access control
     */
    public function __construct(UserAccessControl $uac)
    {
        parent::__construct();
        $this->uac = $uac;
    }

    /**
     * Execute the form if it is valid.
     *
     * First validates the form, then calls the `_execute()` hook method.
     * This hook method can be implemented in subclasses to perform
     * the action of the form. This may be sending email, interacting
     * with a remote API, or anything else you may need.
     *
     * @param array $data Form data.
     * @param array<string, mixed> $options List of options.
     * @return bool False on validation failure, otherwise returns the
     *   result of the `_execute()` method.
     */
    public function execute(array $data, array $options = []): bool
    {
        if (!$this->validate($data)) {
            throw new CustomValidationException(
                __('Something went wrong when validating the one-time password.'),
                $this->getErrors()
            );
        }

        return $this->_execute($data);
    }
}
