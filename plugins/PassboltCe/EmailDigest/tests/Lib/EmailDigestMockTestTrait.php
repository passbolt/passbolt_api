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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
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

    /**
     * Helper method to save multiple email queue entities for particular user.
     * Useful to test threshold scenarios.
     *
     * @param array $data Data to override.
     * @param array $templateBodyVars Template body data to override.
     * @param int $times Number of entities to create.
     * @return void
     */
    protected function persistEmailQueueEntities(array $data = [], array $templateBodyVars = [], int $times = 15): void
    {
        $default = [
            'email' => 'foo@test.test',
            'from_name' => null,
            'from_email' => null,
            'template' => 'LU/emailQueueTemplate',
            'template_vars' => json_encode([
                'body' => array_merge([
                    'owner' => UserFactory::make()->user()->withAvatar()->persist(),
                    'resource' => ResourceFactory::make()->persist(),
                    'showUsername' => true,
                    'showUri' => true,
                    'showDescription' => true,
                    'showSecret' => true,
                    'fullBaseUrl' => 'https://example.test',
                    'secret' => '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----',
                ], $templateBodyVars),
                'title' => 'Foo shared the password',
                'locale' => 'en-UK',
            ]),
        ];

        EmailQueueFactory::make(array_merge($default, $data), $times)->persist();
    }
}
