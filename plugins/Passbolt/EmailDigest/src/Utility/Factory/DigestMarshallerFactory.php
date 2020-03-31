<?php
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
 * @since         2.14.0
 */

namespace Passbolt\EmailDigest\Utility\Factory;

use Cake\Event\EventManager;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerRegisterEvent;
use Passbolt\EmailDigest\Utility\Marshaller\Type\ByTemplateAndExecutedByDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MaximumThresholdSwitchDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MinimumThresholdSwitchDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\PoolDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\SingleEmailDigestMarshaller;

/**
 * This factory creates the digests marshaller. It should only return instances of DigestMarshallerInterface.
 *
 * @package Passbolt\EmailDigest\Utility\Factory
 */
class DigestMarshallerFactory
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * @var EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @var DigestMarshallerPool
     */
    private $digestMarshallerPool;

    /**
     * @var bool
     */
    private $isMarshallerRegisterEventDispatched;

    /**
     * @param EmailPreviewFactory|null $emailPreviewFactory Factory
     * @param DigestMarshallerPool|null $digestMarshallerPool MarshallerPool
     */
    private function __construct(
        EmailPreviewFactory $emailPreviewFactory = null,
        DigestMarshallerPool $digestMarshallerPool = null
    ) {
        $this->isMarshallerRegisterEventDispatched = false;
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
        $this->digestMarshallerPool = $digestMarshallerPool ?? DigestMarshallerPool::getInstance();
    }

    /**
     * Return a singleton of the DigestMarshallerPool
     * @return DigestMarshallerFactory
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param DigestMarshallerInterface $digestMarshallerAtThreshold Marshaller to use at threshold
     * @param int $minimumThreshold Threshold value
     * @return MinimumThresholdSwitchDigestMarshaller
     */
    public function createThresholdMinimumSwitchDigestMarshaller(
        DigestMarshallerInterface $digestMarshallerAtThreshold,
        int $minimumThreshold = 2
    ) {
        return new MinimumThresholdSwitchDigestMarshaller(
            $digestMarshallerAtThreshold,
            $this->createSingleEmailDigestMarshaller(),
            $minimumThreshold
        );
    }

    /**
     * @return SingleEmailDigestMarshaller
     */
    public function createSingleEmailDigestMarshaller()
    {
        return new SingleEmailDigestMarshaller($this->emailPreviewFactory);
    }

    /**
     * @param string $subject Subject to use for the email digest created by marshaller
     * @param string $executedByTemplateVarKey Name of the variable in template vars body which contain the user
     * @return ByTemplateAndExecutedByDigestMarshaller
     */
    public function createByTemplateAndExecutedByEmailDigestMarshaller(string $subject, string $executedByTemplateVarKey)
    {
        return new ByTemplateAndExecutedByDigestMarshaller($subject, $executedByTemplateVarKey, $this->emailPreviewFactory);
    }

    /**
     * @return PoolDigestMarshaller
     */
    public function createPoolDigestMarshaller()
    {
        if (!$this->isMarshallerRegisterEventDispatched) {
            // Dispatch an event to offer possibility to other components to register more email marshallers.
            // Event must be dispatched only once to avoid unnecessary extra registration
            EventManager::instance()->dispatch(DigestMarshallerRegisterEvent::create($this->digestMarshallerPool));
            $this->isMarshallerRegisterEventDispatched = true;
        }

        return new PoolDigestMarshaller($this->digestMarshallerPool);
    }

    /**
     * @param ByTemplateAndExecutedByDigestMarshaller $digestMarshaller Marshaller
     * @param int $maximumThreshold Maximum Threshold
     * @param callable $onThresholdCallback Callback function to execute when threshold is reached.
     * @return MaximumThresholdSwitchDigestMarshaller
     */
    public function createThresholdMaximumSwitchDigestMarshaller(
        ByTemplateAndExecutedByDigestMarshaller $digestMarshaller,
        int $maximumThreshold,
        callable $onThresholdCallback
    ) {
        return new MaximumThresholdSwitchDigestMarshaller($digestMarshaller, $maximumThreshold, $onThresholdCallback);
    }
}
