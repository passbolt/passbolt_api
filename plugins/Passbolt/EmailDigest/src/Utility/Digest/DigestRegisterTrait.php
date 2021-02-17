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
 * @since         3.0.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

/**
 * Components interested into adding new digest through the event system can use this convenience trait.
 * It eases the registration of digests by providing boilerplate code to add new digest instances to the digest pool.
 *
 * Only the method "addDigest" needs to be implemented by registers.
 */
trait DigestRegisterTrait
{
    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            DigestRegisterEvent::EVENT_NAME => $this,
        ];
    }

    /**
     * A class registering digests must implement this method
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestsPool $digestsPool digest pool
     * @return mixed
     */
    abstract public function addDigestsPool(DigestsPool $digestsPool);

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestRegisterEvent $event An instance of the event
     * @return void
     */
    public function __invoke(DigestRegisterEvent $event)
    {
        $this->addDigestsPool($event->getEmailDigestsPool());
    }
}
