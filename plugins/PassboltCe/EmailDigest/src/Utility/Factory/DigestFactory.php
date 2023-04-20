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

namespace Passbolt\EmailDigest\Utility\Factory;

use Cake\Event\EventManager;
use Passbolt\EmailDigest\Utility\Digest\DigestRegisterEvent;
use Passbolt\EmailDigest\Utility\Digest\DigestsCollection;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailDigest\Utility\Digest\SingleDigest;

/**
 * This factory creates the class building the email digests.
 * It should only return instances of DigestInterface.
 *
 * @package Passbolt\EmailDigest\Utility\Factory
 */
class DigestFactory
{
    /**
     * @var static|null
     */
    private static $instance = null;

    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\DigestsPool
     */
    private $digestsPool;

    /**
     * @var bool
     */
    private $isDigestRegisterEventDispatched;

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestsPool|null $digestsPool DigestPool
     */
    final private function __construct(
        ?DigestsPool $digestsPool = null
    ) {
        $this->isDigestRegisterEventDispatched = false;
        $this->digestsPool = $digestsPool ?? DigestsPool::getInstance();
    }

    /**
     * Return a singleton of the DigestsPool
     *
     * @return \Passbolt\EmailDigest\Utility\Factory\DigestFactory
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Clear the singleton of the DigestFactory
     *
     * @return void
     */
    public static function clearInstance(): void
    {
        static::$instance = null;
    }

    /**
     * Factory method for DigestsCollection
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\SingleDigest
     */
    public function createSingleDigest(): SingleDigest
    {
        return new SingleDigest();
    }

    /**
     * Factory method for DigestsCollection
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\DigestsCollection
     */
    public function createDigestsCollection(): DigestsCollection
    {
        if (!$this->isDigestRegisterEventDispatched) {
            // Dispatch an event to offer possibility to other components to register more digests.
            // Event must be dispatched only once to avoid unnecessary extra registration
            EventManager::instance()->dispatch(DigestRegisterEvent::create($this->digestsPool));
            $this->isDigestRegisterEventDispatched = true;
        }

        return new DigestsCollection($this->digestsPool);
    }
}
