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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Webauthn;

use App\Utility\UserAccessControl;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;

class WebauthnVerifyForm extends MfaForm
{
    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService $service Assertion service.
     */
    public function __construct(
        UserAccessControl $uac,
        private readonly MfaWebauthnVerifyAssertionService $service,
    ) {
        parent::__construct($uac);
    }

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('handle', ['type' => 'string'])
            ->addField('credential', ['type' => 'text']);
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('handle', __('The ceremony handle should be a valid UUID.'))
            ->requirePresence('handle', true, __('A ceremony handle is required.'))
            ->notEmptyString('handle', __('The ceremony handle should not be empty.'));

        $validator
            ->array('credential', __('The credential payload should be a valid WebAuthn response.'))
            ->requirePresence('credential', true, __('A credential payload is required.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function process(array $data): bool
    {
        $this->service->verify(
            $this->uac,
            (string)$data['handle'],
            (array)$data['credential'],
        );

        return true;
    }
}
