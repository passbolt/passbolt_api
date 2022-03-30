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
 * @since         3.6.0
 */
namespace App\Test\Factory\Traits;

trait ArmoredKeyFactoryTrait
{
    /**
     * @return $this
     */
    public function expiredKey()
    {
        return $this
            ->setField('fingerprint', 'BD92B8DE3FCF8DD5D60A4DF91E5E3B142396F2C7')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key'));
    }

    /**
     * @return $this
     */
    public function revokedKey()
    {
        return $this
            ->setField('fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'));
    }

    /**
     * @return $this
     */
    public function rsa4096Key()
    {
        return $this
            ->setField('fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));
    }

    /**
     * @return $this
     */
    public function rsa4096Key_2()
    {
        return $this
            ->setField('fingerprint', '23C6C30E241324C90A44A719A86A7EA3739797F5')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'));
    }

    /**
     * @return $this
     */
    public function rsa2048Key()
    {
        return $this
            ->setField('fingerprint', '26FD986838F4F9AB318FF56AE5DFCEE142949B78')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key'));
    }

    /**
     * @return $this
     */
    public function adaPublicKey()
    {
        return $this
            ->setField('fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('armored_key', file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'));
    }
}
