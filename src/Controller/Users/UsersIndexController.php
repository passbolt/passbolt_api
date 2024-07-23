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
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal;
use App\Database\Type\ISOFormatDateTimeType;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\Query\IsMfaEnabledQueryService;

/**
 * @property \BryanCrowe\ApiPagination\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class UsersIndexController extends AppController
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'Users',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'Profiles.first_name',
            'Profiles.last_name',
            'Profiles.created',
            'Profiles.modified',
            'Users.username',
            'Users.created',
            'Users.modified',
            'Users.last_logged_in',
        ],
        'order' => [
            'Users.username' => 'asc', // Default sorted field
        ],
    ];

    /**
     * @return void
     */
    public function index()
    {
        $this->assertJson();

        $findIndexOptions = (new FindIndexOptions())
            ->allowContains([
                'last_logged_in', 'groups_users', 'gpgkey', 'profile', 'role',
                // @deprecate when v2.13 support drops
                'LastLoggedIn', // remapped to last_logged_in in QueryStringComponent
            ])
            ->allowFilters(['search', 'has-groups', 'has-access', 'is-admin']);

        if ($this->User->isAdmin()) {
            $findIndexOptions->allowFilter('is-active');
        }

        // Get additional filters, contain, etc. from plugin
        $event = ControllerFindIndexOptionsBeforeMarshal::create($findIndexOptions, $this);
        $this->getEventManager()->dispatch($event);
        $computedFindIndexOptions = $this->QueryString->get(
            $event->getOptions()->getAllowedOptions(),
            $event->getOptions()->getFilterValidators()
        );

        $this->assertHasAccess($computedFindIndexOptions);

        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $users = $Users->findIndex($this->User->role(), $computedFindIndexOptions);

        if ($this->isFeaturePluginEnabled('MultiFactorAuthentication')) {
            (new IsMfaEnabledQueryService())->decorateAndFilterForIndex(
                $users,
                $this->User->getAccessControl(),
                $computedFindIndexOptions
            );
        }

        $this->paginate($users);
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();
        $this->success(__('The operation was successful.'), $users);
    }

    /**
     * @throws \Cake\Http\Exception\ForbiddenException if user doesn't have access to the resource requested by the filter
     * @throws \Cake\Http\Exception\BadRequestException if multiple has-access filters are requested
     * @param array $options from
     * @return void
     */
    public function assertHasAccess(array $options): void
    {
        if (isset($options['filter']['has-access']) && count($options['filter']['has-access'])) {
            if (count($options['filter']['has-access']) > 1) {
                throw new BadRequestException(__('Multiple has-access filters are not supported.'));
            }
            $resourceId = $options['filter']['has-access'][0];
            $service = new UserHasPermissionService();
            if (!$service->check(PermissionsTable::RESOURCE_ACO, $resourceId, $this->User->id())) {
                throw new ForbiddenException(__('This operation is not allowed for this user.'));
            }
        }
    }
}
