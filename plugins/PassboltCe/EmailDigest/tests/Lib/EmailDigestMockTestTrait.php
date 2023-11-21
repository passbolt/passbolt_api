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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

trait EmailDigestMockTestTrait
{
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
