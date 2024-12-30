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
namespace Passbolt\Metadata\Controller\Upgrade;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

/**
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
abstract class BaseMetadataResourcesIndexController extends AppController
{
    public const MAX_PAGINATION_LIMIT = 200;
    public const MIN_PAGINATION_LIMIT = 1;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * The configuration key defining the pagination
     *
     * @return string
     */
    abstract protected function getDefaultPaginationConfigurationKey(): string;

    /**
     * Defines the error message if the pagination configuration value is not valid
     *
     * @return string
     */
    abstract protected function getInvalidPaginationConfigurationMessage(): string;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('ApiPagination', [
            'model' => 'Resources',
        ]);
        $this->Resources = $this->fetchTable('Resources');
        $this->setPaginationOptions();
        $this->unsetDisallowedPaginationParams();
    }

    /**
     * Set pagination options.
     *
     * @return void
     */
    private function setPaginationOptions(): void
    {
        $limit = Configure::read($this->getDefaultPaginationConfigurationKey());

        if (!is_int($limit)) {
            throw new InternalErrorException(
                $this->getInvalidPaginationConfigurationMessage() . ' ' .
                'Please contact your administrator.'
            );
        }

        $this->paginate['limit'] = $limit;
        if ($limit > self::MAX_PAGINATION_LIMIT) {
            $this->paginate['limit'] = self::MAX_PAGINATION_LIMIT;
        } elseif ($limit < self::MIN_PAGINATION_LIMIT) {
            $this->paginate['limit'] = self::MIN_PAGINATION_LIMIT;
        }

        $this->paginate['maxLimit'] = self::MAX_PAGINATION_LIMIT;
    }

    /**
     * Remove pagination query parameters, those are controlled by configuration for security reasons.
     *
     * @return void
     */
    private function unsetDisallowedPaginationParams(): void
    {
        $params = $this->getRequest()->getQueryParams();
        unset($params['page']);
        unset($params['limit']);
        $request = $this->getRequest()->withQueryParams($params);
        $this->setRequest($request);
    }
}
