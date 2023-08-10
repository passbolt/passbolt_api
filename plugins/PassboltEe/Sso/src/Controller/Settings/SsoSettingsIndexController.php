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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller\Settings;

use App\Controller\AppController;
use Cake\ORM\Locator\LocatorAwareTrait;

class SsoSettingsIndexController extends AppController
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Sso\Model\Table\SsoSettingsTable $SsoSettings
     */
    private $SsoSettings;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        /** @phpstan-ignore-next-line */
        $this->SsoSettings = $this->fetchTable('Passbolt/Sso.SsoSettings');
        $this->loadComponent('ApiPagination', ['model' => 'SsoSettings']);
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $this->User->assertIsAdmin();

        $settings = $this->SsoSettings->find()
            ->select(['id', 'provider', 'created', 'modified', 'created_by', 'modified_by']);
        $this->paginate($settings);

        $this->success(__('The operation was successful.'), $settings);
    }
}
