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
 * @since         4.5.0
 */
namespace App\Test\Lib\Utility;

use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\Routing\Router;

trait HealthcheckRequestTestTrait
{
    /**
     * @before
     * @after
     */
    public function clearMockResponses()
    {
        Client::clearMockResponses();
    }

    /**
     * @param int $code response code
     * @return Client
     */
    public function getMockedHealthcheckStatusRequest(int $code = 200): Client
    {
        $client = new Client();
        $response = (new Response())->withStatus($code);
        $url = Router::url('/healthcheck/status.json', true);
        $client::addMockResponse('GET', $url, $response);

        return $client;
    }
}
