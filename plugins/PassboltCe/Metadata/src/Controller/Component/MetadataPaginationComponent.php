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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;

/**
 * MetadataPagination component
 */
class MetadataPaginationComponent extends Component
{
    public const MAX_PAGINATION_LIMIT = 200;
    public const MIN_PAGINATION_LIMIT = 1;

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [
        'maxLimit' => self::MAX_PAGINATION_LIMIT,
    ];

    /**
     * Overwrites pagination limit with the one defined in configuration
     * Unsets in the query the limit and page options
     *
     * @param array $config configuration
     * @return void
     * @throws \Exception
     */
    public function initialize(array $config): void
    {
        $this->getController()->loadComponent('ApiPagination', $config);
        $this->getController()->paginate['order'] = $config['order'] ?? [];
        $this->getController()->paginate['limit'] = $config['limit'] ?? [];
        $this->modifyPaginationOptionsInRequest();
    }

    /**
     * Remove/modify pagination query parameters, those are controlled by configuration for security reasons.
     *
     * @return void
     */
    private function modifyPaginationOptionsInRequest(): void
    {
        $params = $this->getController()->getRequest()->getQueryParams();

        // page is not allowed to be controlled
        unset($params['page']);

        // limit should be enforced via config
        $limit = $this->getConfigurationLimit();
        $params['limit'] = $limit;

        $request = $this->getController()->getRequest()->withQueryParams($params);
        $this->getController()->setRequest($request);
    }

    /**
     * @throws \Cake\Http\Exception\InternalErrorException When config value is invalid.
     * @return mixed
     */
    public function getConfigurationLimit()
    {
        $limit = Configure::read('passbolt.plugins.metadata.defaultPaginationLimit');

        if (!is_int($limit)) {
            $message = __('Invalid pagination limit set for metadata endpoint.') . ' ' .
                __('Please contact your administrator.');
            Log::error($message);
            throw new InternalErrorException($message);
        }

        return max(min($limit, self::MAX_PAGINATION_LIMIT), self::MIN_PAGINATION_LIMIT);
    }
}
