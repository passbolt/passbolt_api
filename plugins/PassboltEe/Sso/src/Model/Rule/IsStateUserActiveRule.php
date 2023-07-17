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
 * @since         2.0.0
 */

namespace Passbolt\Sso\Model\Rule;

use App\Model\Rule\IsActiveRule;
use Cake\Datasource\EntityInterface;
use Passbolt\Sso\Model\Entity\SsoState;

class IsStateUserActiveRule extends IsActiveRule
{
    /**
     * @inheritDoc
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        // Do not apply rule for sso_recover
        if ($entity->get('type') === SsoState::TYPE_SSO_RECOVER) {
            return true;
        }

        return parent::__invoke($entity, $options);
    }
}
