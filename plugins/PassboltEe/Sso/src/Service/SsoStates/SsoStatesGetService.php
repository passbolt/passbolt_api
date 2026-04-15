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
 * @since         3.11.0
 */
namespace Passbolt\Sso\Service\SsoStates;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Sso\Model\Entity\SsoState;

class SsoStatesGetService
{
    use LocatorAwareTrait;

    /**
     * @param string $state State to find.
     * @return \Passbolt\Sso\Model\Entity\SsoState
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When given state doesn't exist or not active.
     * @throws \Cake\Http\Exception\BadRequestException If given SSO state is invalid.
     */
    public function getOrFail(string $state): SsoState
    {
        if (!SsoState::isValidState($state)) {
            throw new BadRequestException(__('The SSO state is invalid.'));
        }

        /** @var \Passbolt\Sso\Model\Table\SsoStatesTable $ssoStatesTable */
        $ssoStatesTable = $this->fetchTable('Passbolt/Sso.SsoStates');

        try {
            /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
            $ssoState = $ssoStatesTable
                ->find('active')
                ->where(['state' => $state])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException(__('The SSO state does not exist.'), 400, $e);
        }

        return $ssoState;
    }
}
