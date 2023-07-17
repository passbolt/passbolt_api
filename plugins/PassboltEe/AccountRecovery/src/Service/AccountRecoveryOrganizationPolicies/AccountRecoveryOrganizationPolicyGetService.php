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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * Class AccountRecoveryOrganizationPolicyGetService
 */
class AccountRecoveryOrganizationPolicyGetService implements AccountRecoveryOrganizationPolicyGetServiceInterface
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable
     */
    protected $AccountRecoveryOrganizationPolicies;

    /**
     * AccountRecoveryOrganizationPolicyGetService constructor.
     *
     * @param \Cake\Http\ServerRequest|null $serverRequest Server request
     */
    public function __construct(?ServerRequest $serverRequest = null)
    {
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryOrganizationPolicies = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
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
     * Throw an exception if the organization policy is disabled or
     * if the public key is empty
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     * @throws \Cake\Http\Exception\BadRequestException if the feature is not enabled
     * @throws \Cake\Http\Exception\BadRequestException if the public key is empty
     */
    public function getOrFail(): AccountRecoveryOrganizationPolicy
    {
        $policy = $this->get();
        if ($policy->isDisabled()) {
            throw new BadRequestException(__('Account recovery is disabled.'));
        } elseif (is_null($policy->account_recovery_organization_public_key)) {
            throw new BadRequestException(__('The account recovery organization public key is not set.'));
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
    protected function containCreator(Query $query): void
    {
        $contain = $this->request->getQuery('contain');
        if (is_array($contain) && isset($contain['creator']) && $contain['creator']) {
            $query->contain(['Creator.Profiles' => AvatarsTable::addContainAvatar()]);
        }
        if (is_array($contain) && isset($contain['creator.gpgkey']) && $contain['creator.gpgkey']) {
            $query
                ->contain(['Creator.Profiles' => AvatarsTable::addContainAvatar()])
                ->contain('Creator.Gpgkeys', function (Query $q) {
                    return $q->select();
                });
        }
    }
}
