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
namespace Passbolt\Metadata\Event;

use App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal;
use App\Controller\Users\UsersIndexController;
use App\Controller\Users\UsersViewController;
use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\UsersTable;
use Cake\Collection\CollectionInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Handles allowing and adding the `missing_metadata_key_ids` contain.
 */
class MissingMetadataKeyIdsContainListener implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @var bool
     */
    private bool $isContainPresent = false;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ControllerFindIndexOptionsBeforeMarshal::EVENT_NAME => 'allowMissingMetadataKeyIdsContain',
            'Controller.initialize' => 'assertUserHasAccess',
            TableFindIndexBefore::EVENT_NAME => 'containMissingMetadataKeyIds',
        ];
    }

    /**
     * @param \App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal $event The event.
     * @return void
     */
    public function allowMissingMetadataKeyIdsContain(ControllerFindIndexOptionsBeforeMarshal $event): void
    {
        $controller = $event->getController();
        if (!$controller instanceof UsersIndexController) {
            return;
        }

        $options = $event->getOptions();
        $options->allowContain('missing_metadata_key_ids');
    }

    /**
     * Checks if requested contain is allowed to be viewed by the user or not.
     *
     * @param \Cake\Event\EventInterface $event The event object.
     * @return void
     */
    public function assertUserHasAccess(EventInterface $event): void
    {
        $controller = $event->getSubject();
        $request = $controller->getRequest();
        $isUsersIndexRequest = $controller instanceof UsersIndexController;
        $isUsersViewRequest = $controller instanceof UsersViewController;

        if (!$isUsersIndexRequest && !$isUsersViewRequest) {
            return;
        }

        $isContainPresent = (bool)$request->getQuery('contain.missing_metadata_key_ids', false);
        if (!$isContainPresent) {
            return;
        }

        $uac = $this->getUacInRequest($request);
        $isUsersViewMeRequest = $isUsersViewRequest &&
            ($request->getParam('id') === 'me' || $request->getParam('id') === $uac->getId());

        // With the exception of user view requests made by the currently signed-in user to retrieve their own
        // information, only an administrator can use this contain.
        if (!$isUsersViewMeRequest && !$uac->isAdmin()) {
            throw new ForbiddenException(
                __('The missing_metadata_key_ids contain is only allowed for administrators.')
            );
        }

        $this->isContainPresent = true;
    }

    /**
     * Add contain data if the `missing_metadata_key_ids` is requested in the query.
     *
     * @param \App\Model\Event\TableFindIndexBefore $event Event.
     * @return void
     */
    public function containMissingMetadataKeyIds(TableFindIndexBefore $event): void
    {
        $table = $event->getSubject();
        if (!($table instanceof UsersTable)) {
            return;
        }
        // only add contain if requested via query parameters
        if (!$this->isContainPresent) {
            return;
        }

        $query = $event->getQuery();
        $query->contain('MetadataPrivateKeys', function (Query $q) {
            return $q->select(['user_id', 'metadata_key_id']);
        });

        $metadataKeysQuery = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys')->find();
        $metadataKeys = $metadataKeysQuery
            ->select(['id'])
            ->where([$metadataKeysQuery->newExpr()->isNull('deleted')])
            ->toArray();
        $metadataKeysIds = Hash::extract($metadataKeys, '{n}.id');

        $query->formatResults(function (CollectionInterface $results) use ($metadataKeysIds) {
            return $results->map(function (EntityInterface $entity) use ($metadataKeysIds) {
                // Since CakePHP 5, formatResults is being called several times.
                // Thus, if the metadata_private_keys was already unset, we can skip the rest of the code.
                if (!isset($entity['metadata_private_keys'])) {
                    return $entity;
                }
                if ($entity->get('active')) {
                    // get missing metadata keys
                    $userMetadataKeysIds = Hash::extract($entity, 'metadata_private_keys.{n}.metadata_key_id');
                    if (empty($userMetadataKeysIds)) {
                        $missingUserMetadataKeysIds = $metadataKeysIds;
                    } else {
                        $missingUserMetadataKeysIds = array_values(array_diff($metadataKeysIds, $userMetadataKeysIds));
                    }
                    $entity['missing_metadata_key_ids'] = $missingUserMetadataKeysIds;
                } else {
                    // if user is not active then no need to set as it's expected to that metadata keys is missing
                    $entity['missing_metadata_key_ids'] = [];
                }

                // unset metadata private keys array since it's only required for the diff
                unset($entity['metadata_private_keys']);

                return $entity;
            });
        });
    }
}
