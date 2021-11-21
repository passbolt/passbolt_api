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
 * @since         2.13.0
 */
namespace Passbolt\EmailDigest\Test\Lib;

use App\Model\Entity\Profile;
use App\Model\Entity\User;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Digest\AbstractDigest;
use Passbolt\EmailDigest\Utility\Digest\DigestInterface;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;

trait EmailDigestMockTestTrait
{
    /**
     * Create a new digest at runtime for testing.
     *
     * @param bool $canAddToDigests Returned by canAddToDigests method of the digest.
     * @param array $digests Some email digests that the digest must return
     * @return \Passbolt\EmailDigest\Utility\Digest\DigestInterface
     */
    public function createDigest(bool $canAddToDigests, array $digests): DigestInterface
    {
        return new class ($canAddToDigests, $digests) extends AbstractDigest
        {
            private $canAddToDigests;
            private $emailDigests = [];

            public function __construct(bool $canAddToDigests, array $emailDigests)
            {
                $this->canAddToDigests = $canAddToDigests;
                $this->emailDigests = $emailDigests;
            }

            public function marshalEmails(): array
            {
                return $this->emailDigests;
            }

            public function canAddToDigest(Entity $emailQueueEntity)
            {
                return $this->canAddToDigests;
            }
        };
    }

    /**
     * Create an email queue entity with the passed properties.
     *
     * @param array|null $properties properties for the email queue entity
     * @param array|null $templateBodyVars variables for email body
     * @return Entity
     */
    protected function createEmailQueueEntity(?array $properties = [], ?array $templateBodyVars = []): Entity
    {
        $defaultProperties = [
            'id' => 1,
            'subject' => 'Subject of the email',
            'email' => 'email_queue_recipient@passbolt.com',
            'template' => 'LU/emailQueueTemplate.php',
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
     * Create an email digest
     *
     * @param array $emailsData Array of emails queue entity
     * @param array|null $properties props
     * @return EmailDigest
     */
    protected function createEmailDigest(?array $emailsData = [], ?array $properties = []): EmailDigest
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

    /**
     * Create a User Entity for the email
     *
     * @param string $username email
     * @param string $firstName first name
     * @return User
     */
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
