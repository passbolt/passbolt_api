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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Form\Duo;

use App\Utility\UserAccessControl;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Duo\Web;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyForm extends MfaForm
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    protected $settings;

    /**
     * VerifyForm constructor.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $settings settings
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        parent::__construct($uac);
        $this->settings = $settings;
    }

    /**
     * Build form schema
     *
     * @param \Cake\Form\Schema $schema schema
     * @return $this|\Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('sig_response', ['type' => 'string']);
    }

    /**
     * Build form validation
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('sig_response', __('A signature is required.'))
            ->notEmpty('sig_response', __('The signature should not be empty.'))
            ->add('sig_response', ['isValidSigResponse' => [
                'rule' => [$this, 'isValidSigResponse'],
                'message' => __('This is not a valid signature response.'),
            ]]);

        return $validator;
    }

    /**
     * Verify a response signed by Duo
     *
     * @param string $value signature response value
     * @return bool
     */
    public function isValidSigResponse(string $value)
    {
        $orgSettings = $this->settings->getOrganizationSettings();
        $salt = $orgSettings->getDuoSalt();
        $secretKey = $orgSettings->getDuoSecretKey();
        $integrationKey = $orgSettings->getDuoIntegrationKey();
        $sigRequest = Web::verifyResponse($integrationKey, $secretKey, $salt, $value);

        return $sigRequest === $this->uac->getUsername();
    }

    /**
     * Get a duo signature request using Org settings conf
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if OrgSettings are not complete
     * @return string
     */
    public function getSigRequest()
    {
        $orgSettings = $this->settings->getOrganizationSettings();
        $salt = $orgSettings->getDuoSalt();
        $secretKey = $orgSettings->getDuoSecretKey();
        $integrationKey = $orgSettings->getDuoIntegrationKey();
        $sigRequest = Web::signRequest($integrationKey, $secretKey, $salt, $this->uac->getUsername());

        return $sigRequest;
    }
}
