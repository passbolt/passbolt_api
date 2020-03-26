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
namespace Passbolt\EmailDigest\Test\Lib;

use App\Model\Entity\Profile;
use App\Model\Entity\User;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\Type\AbstractDigestMarshaller;

trait EmailDigestMockTestTrait
{
    /**
     * Create a new digests marshaller at runtime for testing.
     * @param bool $canMarshallDigests Returned by canMarshallDigests method of the marshaller.
     * @param array $digests Some digests that the marshaller must return
     * @return DigestMarshallerInterface
     */
    public function createMarshaller(bool $canMarshallDigests, array $digests)
    {
        return new class ($canMarshallDigests, $digests) extends AbstractDigestMarshaller
        {
            private $canMarshallDigests;
            private $digests = [];

            public function __construct(bool $canMarshallDigests, array $digests)
            {
                $this->canMarshallDigests = $canMarshallDigests;
                $this->digests = $digests;
            }

            public function marshalDigests()
            {
                return $this->digests;
            }

            public function canMarshalDigestsFrom(Entity $emailQueueEntity)
            {
                return $this->canMarshallDigests;
            }
        };
    }

    /**
     * Create an email queue entity with the passed properties.
     * @param array $properties Properties for the email queue entity
     * @return Entity
     */
    protected function createEmailQueueEntity(array $properties = [], array $templateBodyVars = [])
    {
        $defaultProperties = [
            'id' => 1,
            'subject' => 'Subject of the email',
            'email' => 'email_queue_recipient@passbolt.com',
            'template' => 'LU/emailQueueTemplate.ctp',
            'template_vars' => [
                'body' => array_merge([
                    'owner' => new User(),
                    'text' => 'Content of the email',
                ], $templateBodyVars),
            ],
        ];

        return new Entity(array_merge($defaultProperties, $properties));
    }

    /**
     * @param Entity[] $emailsData Array of emails queue entity
     * @return EmailDigest
     */
    protected function createEmailDigest(array $emailsData = [], array $properties = [])
    {
        $defaultProperties = [
            'recipient' => 'digest_recipient@passbolt.com',
            'subject' => 'Subject of the email digest',
            'content' => 'Content of the email digest',
        ];

        $properties = array_merge($defaultProperties, $properties);

        $emailDigest = new EmailDigest();
        $emailDigest->setEmailRecipient($properties['recipient']);
        $emailDigest->setSubject($properties['subject']);
        $emailDigest->setContent($properties['content']);

        foreach ($emailsData as $emailData) {
            $emailDigest->addEmailData($emailData);
        }

        return $emailDigest;
    }

    protected function createUserForEmail(string $username, string $firstName)
    {
        return new User([
            'username' => $username,
            'profile' => new Profile([
                'first_name' => $firstName,
            ]),
        ]);
    }
}
