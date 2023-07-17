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
namespace Passbolt\Sso\Service\SsoKeys;

use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Sso\Model\Entity\SsoKey;

class SsoKeysCreateService
{
    /**
     * Build entity and perform basic check.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data data
     * @return \Passbolt\Sso\Model\Entity\SsoKey
     */
    public function create(UserAccessControl $uac, array $data): SsoKey
    {
        $SsoKeys = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoKeys');

        /** @var \Passbolt\Sso\Model\Entity\SsoKey $ssoKey entity */
        $ssoKey = $SsoKeys->newEntity([
            'user_id' => $uac->getId(),
            'data' => $data['data'] ?? '',
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ], [
            'accessibleFields' => [
                'user_id' => true,
                'data' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        // check validation & build rules
        $msg = __('The SSO key could not be saved.');
        if ($ssoKey->getErrors()) {
            throw new ValidationException($msg, $ssoKey, $SsoKeys);
        }
        $SsoKeys->checkRules($ssoKey);
        if ($ssoKey->getErrors()) {
            throw new ValidationException($msg, $ssoKey, $SsoKeys);
        }

        if (!$SsoKeys->save($ssoKey, ['checkrules' => false])) {
            throw new InternalErrorException($msg . ' ' . __('Please try again later.'));
        }

        return $ssoKey;
    }
}
