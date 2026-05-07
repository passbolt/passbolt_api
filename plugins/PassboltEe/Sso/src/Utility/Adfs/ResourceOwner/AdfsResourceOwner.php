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
namespace Passbolt\Sso\Utility\Adfs\ResourceOwner;

use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Utility\OAuth2\ResourceOwner\OAuth2ResourceOwner;

class AdfsResourceOwner extends OAuth2ResourceOwner
{
    private ?string $emailClaimField;

    /**
     * {@inheritDoc}
     *
     * @param array $data Response data.
     * @param string|null $emailClaimField Email claim field.
     */
    public function __construct(array $data = [], ?string $emailClaimField = null)
    {
        parent::__construct($data);

        // Default is upn
        $this->emailClaimField = $emailClaimField ?? SsoSetting::ADFS_EMAIL_CLAIM_UPN;
    }

    /**
     * Retrieves email of the resource owner.
     *
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException When email claim field is not present in the data.
     */
    public function getEmail(): string
    {
        if (!isset($this->data[$this->emailClaimField]) || is_null($this->data[$this->emailClaimField])) {
            $msg = __('Single sign-on failed.') . ' ';
            $msg .= __(
                'The {0} claim is not present, please contact your administrator.',
                $this->emailClaimField
            );
            throw new BadRequestException($msg);
        }

        return $this->data[$this->emailClaimField];
    }
}
