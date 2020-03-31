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

namespace Passbolt\EmailDigest\Service;

use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Factory\DigestMarshallerFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerRegisterEvent;
use Passbolt\EmailDigest\Utility\Marshaller\Type\PoolDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\SingleEmailDigestMarshaller;

/**
 * The MarshallEmailDigestsService is a service designed to aggregate multiple emails together in an email digest.
 *
 * The emails are aggregated following rules defined in what we call the digests marshallers.
 * The purpose of a digest marshaller is to create a collection of digests from a collection of given emails.
 *
 * Each digest marshaller can define the emails that it can marshall and the way it marshals the emails.
 * i.e. two different digest marshallers executed with the same input would not necessarily produces the same output.
 *
 * Rules applied while marshalling emails are the following:
 * One user can have one or many email digests.
 * One email digests is composed of one or many emails entities.
 * One email entity can be only present in one digest.
 *
 * Class MarshallEmailsDigestsService
 * @package Passbolt\EmailDigest\Service
 */
class MarshallEmailsDigestsService
{
    /**
     * @var DigestMarshallerFactory
     */
    private $digestMarshallerFactory;

    /**
     * @param DigestMarshallerFactory $digestMarshallerFactory Factory
     */
    public function __construct(DigestMarshallerFactory $digestMarshallerFactory = null)
    {
        $this->digestMarshallerFactory = $digestMarshallerFactory ?? DigestMarshallerFactory::getInstance();
    }

    /**
     * Handle the emails data for each recipient and transform them on emails digests grouped by recipient.
     * It returns an iterable object so the result can be fetched from the iterable one by one.
     * Once the iterable has been iterated over, it can not be iterated over again unless it is fetched again.
     * This is done to improve the performance and get only digests needed before retrieve the next set of digests.
     *
     * @param array $emails An array of emails entities from email queue.
     * @return iterable|EmailDigestInterface[][]
     * @throws UnsupportedEmailDigestDataException
     */
    public function createDigestsByRecipient(array $emails)
    {
        // Group the emails entities by recipient and create digests for each group of emails.
        foreach ($this->groupEmailsByRecipients($emails) as $emailRecipient => $emailsData) {
            $emailDigests = $this->createDigests($emailsData);
            yield $emailDigests;
        }
    }

    /**
     * Create and return collection of digests from the list of given emails entity. The emails are EmailQueue entities.
     *
     * The SingleEmailDigestMarshaller is used as a default marshaller so if no supported marshallers for the email is found in
     * the collection of available digest marshaller (PoolDigestMarshaller), then it is used and will produce a digest with a single
     * email.
     * @param Entity[] $emailsData A list of emails entities from mailer plugin.
     * @return EmailDigestInterface[]
     * @throws UnsupportedEmailDigestDataException
     * @see SingleEmailDigestMarshaller
     *
     * The PoolDigestMarshaller is a collection of available digests marshallers. These marshallers can been registered
     * anywhere in the code, either by using directly the PoolDigestMarshaller instance or via the DigestMarshallerRegister event.
     * @see DigestMarshallerRegisterEvent
     * @see PoolDigestMarshaller
     *
     * Behavior of the function:
     * Instantiate a new digest marshaller for each groups of emails since we want a given email to be present
     * in only one marshaller at a time and we don't want to reuse the same marshaller for a different user.
     * Once all emails data for the recipient have been added to the digest marshallers, we create the digests.
     *
     * We create emails digests with the default marshaller and the pool marshaller, and merge the digests together.
     */
    private function createDigests(array $emailsData)
    {
        // Initialize a default digest marshaller (SingleEmailDigest) in case an email is not supported by the
        // pool digest marshaller, it will be sent a single item
        $defaultDigestMarshaller = $this->digestMarshallerFactory->createSingleEmailDigestMarshaller();
        $poolDigestMarshaller = $this->digestMarshallerFactory->createPoolDigestMarshaller();

        // Each email is added to a marshaller
        foreach ($emailsData as $emailData) {
            // Choose between the digest marshaller if supported or fallback on the default digest marshaller
            $marshaller = $poolDigestMarshaller->canMarshalDigestsFrom($emailData) ? $poolDigestMarshaller : $defaultDigestMarshaller;
            $marshaller->addEmailEntityToMarshal($emailData);
        }

        // Merge the digests from the default digests marshaller and from the pool digests marshaller
        return array_merge($defaultDigestMarshaller->marshalDigests(), $poolDigestMarshaller->marshalDigests());
    }

    /**
     * Group all the emails entities by recipient so digests can be created per recipient.
     *
     * @param array $emails An array of email entities from email queue.
     * @return Entity[][]
     */
    private function groupEmailsByRecipients(array $emails)
    {
        /** @var Entity[][] $emailsDataGroupedByRecipient */
        $emailsDataGroupedByRecipient = [];

        foreach ($emails as $emailData) {
            $emailsDataGroupedByRecipient[$emailData->email][] = $emailData;
        }

        return $emailsDataGroupedByRecipient;
    }
}
