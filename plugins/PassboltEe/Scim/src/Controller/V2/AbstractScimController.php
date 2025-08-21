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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Controller\V2;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Exception;
use Passbolt\Scim\Utility\Object\ScimErrorResponse;
use Passbolt\Scim\Utility\ScimConstants;
use Passbolt\Scim\Utility\ScimObjectInterface;

abstract class AbstractScimController extends AppController
{
    public const STATUS_CREATED = 201;
    public const STATUS_EDITED = 200;
    public const STATUS_DELETED = 204;
    public const STATUS_BAD_REQUEST = 400;

    /**
     * Before Filter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->disableAutoRender();
        $this->setResponse($this->getResponse()->withType(ScimConstants::CONTENT_TYPE));
    }

    /**
     * Process exception and create response
     *
     * @param string $settingId Setting id
     * @param \Exception $e Exception
     * @return void
     */
    protected function processException(string $settingId, Exception $e): void
    {
        $status = $e->getCode();
        $this->processResponse($settingId, new ScimErrorResponse($e), $status);
    }

    /**
     * Prepare SCIM response, it is called from every endpoint
     *  - Replace {scimUrl} placeholder from JSON response
     *
     * @param string $settingId Org Setting Id
     * @param \Passbolt\Scim\Utility\ScimObjectInterface|array $data Response data
     * @param int $status Status code
     * @return void
     */
    protected function processResponse(string $settingId, ScimObjectInterface|array $data, int $status = 200): void
    {
        if ($data instanceof ScimObjectInterface) {
            $data = $data->toSCIM();
        }
        $scimUrl = str_replace('"', '', json_encode(Router::url('scim/v2/' . $settingId, true)));
        $json = str_replace('{scimUrl}', $scimUrl, json_encode($data, JSON_PRETTY_PRINT));
        $this->setResponse($this->getResponse()->withStringBody($json)->withStatus($status));
    }
}
