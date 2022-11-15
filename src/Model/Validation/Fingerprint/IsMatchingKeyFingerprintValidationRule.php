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
 * @since         3.6.0
 */

namespace App\Model\Validation\Fingerprint;

use App\Model\Validation\PassboltValidationRule;
use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Core\Exception\CakeException;

class IsMatchingKeyFingerprintValidationRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The fingerprint does not match the one of the armored key.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        $armoredKey = $context['data']['armored_key'] ?? null;
        if (empty($armoredKey) || empty($value)) {
            return false;
        }
        if (!is_string($armoredKey) || !is_string($value)) {
            return false;
        }
        try {
            $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        } catch (CakeException $e) {
            $this->setErrorMessage($e->getMessage());

            return false;
        }

        return $keyInfo['fingerprint'] === $value;
    }
}
