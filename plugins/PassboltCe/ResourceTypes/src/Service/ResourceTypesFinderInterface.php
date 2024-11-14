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
 * @since         4.0.0
 */
namespace Passbolt\ResourceTypes\Service;

use Cake\ORM\Query\SelectQuery;

interface ResourceTypesFinderInterface
{
    /**
     * Get resource types query.
     *
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function find(): SelectQuery;

    /**
     * @param  \Cake\ORM\Query\SelectQuery $query query to filter
     * @param array $options options with the filter option
     * @return void
     */
    public function filter(SelectQuery $query, array $options): void;

    /**
     * @param  \Cake\ORM\Query\SelectQuery $query query to filter
     * @param array $options options with the contain option
     * @return void
     */
    public function contain(SelectQuery $query, array $options): void;
}
