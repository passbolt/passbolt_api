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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies;

use App\Model\Table\AvatarsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\ServerRequest;
use Cake\ORM\Query;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicies
 */
class AccountRecoveryOrganizationPolicyGetService implements AccountRecoveryOrganizationPolicyGetServiceInterface
{
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * AccountRecoveryOrganizationPolicyGetService constructor.
     *
     * @param \Cake\Http\ServerRequest|null $serverRequest Server request
     */
    public function __construct(?ServerRequest $serverRequest = null)
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->request = $serverRequest ?? new ServerRequest();
    }

    /**
     * Get the current account recovery policy or fallback on the default one (disabled)
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function get(): AccountRecoveryOrganizationPolicy
    {
        $query = $this->AccountRecoveryOrganizationPolicies->find();
        $this->containCreator($query);

        try {
            $policy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyOrFail($query);
        } catch (RecordNotFoundException $exception) {
            $policy = $this->AccountRecoveryOrganizationPolicies->newEntityForDefaultFallback();
        }

        return $policy;
    }

    /**
     * Join the creator to the query if contained in the request
     * The Gpgkey of the creator may also be contained.
     *
     * @param \Cake\ORM\Query $query Query to decorate
     * @return void
     */
    public function containCreator(Query $query): void
    {
        $contain = $this->request->getQuery('contain');
        if (is_array($contain) && isset($contain['creator'])) {
            $query->contain(['Creator.Profiles' => AvatarsTable::addContainAvatar()]);
        }
        if (is_array($contain) && isset($contain['creator.gpgkey'])) {
            $query
                ->contain(['Creator.Profiles' => AvatarsTable::addContainAvatar()])
                ->contain('Creator.Gpgkeys', function (Query $q) {
                    return $q->select();
                });
        }
    }
}
