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
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;

/**
 * Allow to wrap another marshaller and to define a threshold limit for number of emails that the wrapped marshaller can marshall.
 * Once the threshold limit is reached, it falls back on the threshold marshaller.
 * @package Passbolt\EmailDigest\Utility\Marshaller\Type
 */
class MinimumThresholdSwitchDigestMarshaller extends AbstractDigestMarshaller
{
    /**
     * @var DigestMarshallerInterface
     */
    private $atThresholdDigestMarshaller;

    /**
     * @var int
     */
    private $minimumThreshold;

    /**
     * @var int
     */
    private $emailCount;

    /**
     * @var DigestMarshallerInterface
     */
    private $belowThresholdDigestMarshaller;

    /**
     * @param DigestMarshallerInterface $digestMarshallerAtThreshold The wrapped digest marshaller to use when it goes above limit.
     * @param DigestMarshallerInterface $digestMarshallerBelowThreshold The wrapped digest marshaller to use until it goes above limit.
     * @param int $minimumThreshold The limit until the digest marshaller should be used until falling back on threshold marshaller
     */
    public function __construct(
        DigestMarshallerInterface $digestMarshallerAtThreshold,
        DigestMarshallerInterface $digestMarshallerBelowThreshold,
        int $minimumThreshold
    ) {
        $this->atThresholdDigestMarshaller = $digestMarshallerAtThreshold;
        $this->belowThresholdDigestMarshaller = $digestMarshallerBelowThreshold;
        $this->minimumThreshold = $minimumThreshold;
        $this->emailCount = 0;
    }

    /**
     * @param Entity $emailQueueEntity An email queue entity
     * @return AbstractDigestMarshaller
     */
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        $this->emailCount++;

        return parent::addEmailEntityToMarshal($emailQueueEntity);
    }

    /**
     * Add all the emails to one of the marshaller because we will need to flags attached email when we send the digests
     * @return EmailDigestInterface[]
     */
    public function marshalDigests()
    {
        $marshaller = $this->isThresholdReached() ? $this->atThresholdDigestMarshaller : $this->belowThresholdDigestMarshaller;

        $this->addEmailsToMarshaller($marshaller);

        return $marshaller->marshalDigests();
    }

    /**
     * Both at threshold and below threshold marshaller must be able to marshall the email entity
     * @param Entity $emailQueueEntity An email queue entity
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity)
    {
        return $this->atThresholdDigestMarshaller->canMarshalDigestsFrom($emailQueueEntity) && $this->belowThresholdDigestMarshaller->canMarshalDigestsFrom($emailQueueEntity);
    }

    /**
     * @return bool
     */
    private function isThresholdReached()
    {
        return $this->emailCount > $this->minimumThreshold;
    }

    /**
     * @param DigestMarshallerInterface $marshaller Marshaller
     * @return $this
     */
    private function addEmailsToMarshaller(DigestMarshallerInterface $marshaller)
    {
        foreach ($this->emailsData as $emailData) {
            $marshaller->addEmailEntityToMarshal($emailData);
        }

        return $this;
    }
}
