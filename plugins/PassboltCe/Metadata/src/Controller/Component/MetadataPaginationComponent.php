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
    protected $_defaultConfig = [];

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
        $config = $this->setPaginationOptions($config);
        $this->getController()->loadComponent('ApiPagination', $config);
        $this->getController()->paginate['order'] = $config['order'] ?? [];
        $this->getController()->paginate['limit'] = $config['limit'] ?? [];
        $this->unsetDisallowedPaginationParams();
    }

    /**
     * Set pagination options.
     *
     * @param array $config component configuration
     * @return array
     */
    private function setPaginationOptions(array $config): array
    {
        $limit = Configure::read('passbolt.plugins.metadata.defaultPaginationLimit');

        if (!is_int($limit)) {
            $message = __('Invalid pagination limit set for metadata endpoint.') . ' ' .
                __('Please contact your administrator.');
            Log::error($message);
            throw new InternalErrorException($message);
        }

        $limit = max(min($limit, self::MAX_PAGINATION_LIMIT), self::MIN_PAGINATION_LIMIT);

        return array_merge($config, [
            'limit' => $limit,
            'maxLimit' => self::MAX_PAGINATION_LIMIT,
        ]);
    }

    /**
     * Remove pagination query parameters, those are controlled by configuration for security reasons.
     *
     * @return void
     */
    private function unsetDisallowedPaginationParams(): void
    {
        $params = $this->getController()->getRequest()->getQueryParams();
        unset($params['page']);
        unset($params['limit']);
        $request = $this->getController()->getRequest()->withQueryParams($params);
        $this->getController()->setRequest($request);
    }
}
