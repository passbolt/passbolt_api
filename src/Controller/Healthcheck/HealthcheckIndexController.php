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
namespace App\Controller\Healthcheck;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Client;
use Cake\Http\Exception\ForbiddenException;

class HealthcheckIndexController extends AppController
{
    public const PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED =
        'passbolt.plugins.healthcheck.security.indexEndpointEnabled';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->throwErrorIsEndpointIsDisabled();

        $this->Authentication->allowUnauthenticated(['index']);

        return parent::beforeFilter($event);
    }

    /**
     * Index
     * Display information about the passbolt instance
     * It is only available in debug mode and for logged in administrators
     *
     * @param ?\Cake\Http\Client $client client used to query the healthcheck endpoint
     * @return void
     */
    public function index(?Client $client)
    {
        // Allow access only in debug mode or if logged in as admin
        if (Configure::read('debug') == 0) {
            if ($this->User->role() != Role::ADMIN) {
                throw new ForbiddenException();
            }
        }

        $checks = Healthchecks::all();
        $checks = array_merge($this->__webChecks(), $checks);

        if (!$this->request->is('json')) {
            $this->viewBuilder()
                ->setLayout('login')
                ->setTemplatePath('Healthcheck')
                ->setTemplate('index');
            $this->success(__('All checks ran successfully!'), $checks);
        } else {
            $this->set('checks', $checks);
            $this->viewBuilder()->setOption('serialize', ['checks']);
        }
    }

    /**
     * Check that need/can to be performed in web context only
     *
     * @access private
     * @return array
     */
    private function __webChecks(): array
    {
        $checks['ssl']['is'] = $this->request->is('https');

        return $checks;
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the endpoint is deactivated
     */
    private function throwErrorIsEndpointIsDisabled(): void
    {
        if (!Configure::read(self::PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED)) {
            throw new ForbiddenException(__('Healthcheck security index endpoint disabled.'));
        }
    }
}
