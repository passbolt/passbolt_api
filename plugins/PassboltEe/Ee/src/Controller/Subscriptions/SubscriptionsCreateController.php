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
 * @since         3.1.0
 */
namespace Passbolt\Ee\Controller\Subscriptions;

use App\Controller\AppController;
use App\Error\Exception\PaymentRequiredException;
use App\Model\Entity\Role;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Service\SubscriptionKeySaveService;

/**
 * Class SubscriptionsCreateController
 *
 * @property  \Passbolt\Ee\Model\Table\SubscriptionsTable $Subscriptions
 */
class SubscriptionsCreateController extends AppController
{
    /**
     * @return void
     */
    public function create()
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not allowed to access this location.'));
        }
        $keyString = $this->getRequest()->getData('data', '');
        if (empty($keyString)) {
            throw new BadRequestException(__('Subscription key data is required.'));
        }

        /** @phpstan-ignore-next-line */
        $this->Subscriptions = $this->fetchTable('Passbolt/Ee.Subscriptions');
        try {
            $service = new SubscriptionKeySaveService();
            $keyDto = $service->save($keyString, $this->User->getAccessControl());
        } catch (SubscriptionSignatureException $e) {
            throw new BadRequestException($e->getMessage());
        } catch (SubscriptionException $e) {
            throw new PaymentRequiredException($e->getMessage(), $e->getErrors());
        }

        $this->success(__('The subscription was created.'), $keyDto->toArray());
    }
}
