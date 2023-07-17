<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */
namespace Passbolt\Ee\Service;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;

/**
 * Class SubscriptionKeyGetService
 */
class SubscriptionKeyGetService
{
    use LocatorAwareTrait;

    public const LEGACY_SUBSCRIPTION_FILE = CONFIG . 'license';
    public const SUBSCRIPTION_FILE = CONFIG . 'subscription_key.txt';

    /**
     * @var \Passbolt\Ee\Service\SubscriptionKeyValidateService $SubscriptionValidateService
     */
    protected $SubscriptionValidateService;
    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable
     */
    protected $Subscriptions;

    /**
     * SubscriptionKeyGetService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Subscriptions = $this->fetchTable('Passbolt/Ee.Subscriptions');
        $this->SubscriptionValidateService = new SubscriptionKeyValidateService();
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     */
    public function get(UserAccessControl $uac): SubscriptionKeyDto
    {
        if (!$uac->isAdmin()) {
            throw new ForbiddenException(__('Only administrators can view the subscription details.'));
        }
        $keyString = $this->readFromDB();
        if (!isset($keyString)) {
            $keyString = $this->readFromFile();
        }
        if (!isset($keyString) || empty($keyString)) {
            throw new SubscriptionRecordNotFoundException();
        }

        return $this->SubscriptionValidateService->validate($keyString);
    }

    /**
     * Try to read the key string from database (OrganizationSettings table)
     * Try new file name first then legacy name, log warnings if issues.
     *
     * @return string|null
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException if subscription key is invalid
     */
    protected function readFromDB()
    {
        try {
            return $this->Subscriptions->getOrFail()->get('value');
        } catch (SubscriptionRecordNotFoundException $exception) {
            Log::warning('The subscription key could not be found in the database. Falling back on files.');
            // Try some other ways...
            return null;
        }
    }

    /**
     * Try to read the key string from file
     * Try new file name first then legacy name, log warnings if issues.
     *
     * @return string|null
     */
    protected function readFromFile()
    {
        // New file name
        if (is_readable(self::SUBSCRIPTION_FILE)) {
            return file_get_contents(self::SUBSCRIPTION_FILE);
        } else {
            if (file_exists(self::SUBSCRIPTION_FILE)) {
                Log::warning('The subscription key file exists but is not readable.');
            } else {
                Log::warning('The subscription key could not be found under config/subscription_key.txt');
            }
        }

        // Old file name
        if (is_readable(self::LEGACY_SUBSCRIPTION_FILE)) {
            $key = file_get_contents(self::LEGACY_SUBSCRIPTION_FILE);
            Log::warning('You are using the subscription key legacy file name.');

            return $key;
        } else {
            if (file_exists(self::LEGACY_SUBSCRIPTION_FILE)) {
                Log::warning('The subscription key legacy file exists but is not readable.');
            } else {
                Log::warning('The subscription key could not be found using legacy file.');
            }
        }

        return null;
    }
}
