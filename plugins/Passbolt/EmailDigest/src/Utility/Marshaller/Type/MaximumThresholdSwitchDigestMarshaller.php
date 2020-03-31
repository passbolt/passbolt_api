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

namespace Passbolt\EmailDigest\Utility\Marshaller\Type;

use Cake\ORM\Entity;
use InvalidArgumentException;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;

/**
 * The MinimumThresholdSwitchDigestMarshaller is used to marshall emails with a given email marshaller until a certain
 * amount of emails is added to the marshaller. When this amount of emails is reached, it switch to another marshaller.
 * All emails which were included in the initial marshaller are moved to this other marshaller.
 *
 * e.g. it can be used to create a digest composed of a maximum 50 emails, and switch strategy if there is more and
 * create a new digest with a more condensed information.
 *
 * The callback function must return a collection of digests (which can be an array of zero, one or multiple digests).
 *
 * @package Passbolt\EmailDigest\Utility\Marshaller\Type
 */
class MaximumThresholdSwitchDigestMarshaller extends AbstractDigestMarshaller
{
    /**
     * @var DigestMarshallerInterface
     */
    private $digestMarshaller;

    /**
     * @var int
     */
    private $emailThresholdLimit;

    /**
     * @var int
     */
    private $emailCount;

    /**
     * @var callable
     */
    private $onThresholdCallback;

    /**
     * @var Entity
     */
    private $emailData;

    /**
     * @param DigestMarshallerInterface $digestMarshaller The wrapped digest marshaller to use until it goes above limit.
     * @param int $thresholdLimit The limit until the digest marshaller should be used after what the threshold callback will be called.
     * @param callable $onThresholdCallback Callback function called when threshold is reached.
     * It receives as 1st argument, an email entity, and the number of emails as 2nd argument.
     */
    public function __construct(
        DigestMarshallerInterface $digestMarshaller,
        int $thresholdLimit,
        callable $onThresholdCallback
    ) {
        $this->digestMarshaller = $digestMarshaller;
        $this->emailThresholdLimit = $thresholdLimit;
        $this->emailCount = 0;
        $this->onThresholdCallback = $onThresholdCallback;
    }

    /**
     * @param Entity $emailQueueEntity An email queue entity
     * @return $this|AbstractDigestMarshaller
     */
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        // We add data to the wrapped marshaller only when below the threshold limit (for performance reason only)
        if (!$this->isThresholdReached()) {
            $this->digestMarshaller->addEmailEntityToMarshal($emailQueueEntity);
        }

        $this->emailCount++;

        if ($this->isThresholdReached()) {
            // we save an email entity to pass it to the callback function called when threshold is reached
            $this->emailData = $emailQueueEntity;
        }

        return $this;
    }

    /**
     * @return EmailDigestInterface[]
     */
    public function marshalDigests()
    {
        if ($this->isThresholdReached()) {
            $digests = $this->onThresholdCallback($this->emailData, $this->emailCount);
        } else {
            $digests = $this->digestMarshaller->marshalDigests();
        }

        return $digests;
    }

    /**
     * Return true if it can marshall the given email data into a digests
     * @param Entity $emailQueueEntity An email entity from email queue
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity)
    {
        return $this->digestMarshaller->canMarshalDigestsFrom($emailQueueEntity);
    }

    /**
     * Return whether or not the threshold is reached
     * @return bool
     */
    private function isThresholdReached()
    {
        return $this->emailCount > $this->emailThresholdLimit;
    }

    /**
     * Callback executed when the maximum threshold defined is reached.
     * Must return an array of email digests.
     * @param Entity $emailQueueEntity An email queue entity
     * @param int $emailCount Count of the emails
     * @return EmailDigestInterface[]
     */
    private function onThresholdCallback(Entity $emailQueueEntity, int $emailCount)
    {
        $digests = call_user_func($this->onThresholdCallback, $emailQueueEntity, $emailCount);

        if (!is_array($digests)) {
            throw new InvalidArgumentException('The onThresholdCallback must return an array of ' . EmailDigestInterface::class);
        }

        return $digests;
    }
}
