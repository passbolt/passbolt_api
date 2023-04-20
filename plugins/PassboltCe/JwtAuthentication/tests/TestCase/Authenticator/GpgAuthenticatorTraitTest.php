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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;

class GpgAuthenticatorTraitTest extends TestCase
{
    use \App\Authenticator\GpgAuthenticatorTrait;

    protected $gpg;

    private const VALID_GPG_MESSAGE = <<<EOD
    -----BEGIN PGP MESSAGE-----

    wcDMA0FXlIvWYJWGAQv/W6GmXmgli3XHcol7AoloU1VR4+Gt0phDoNCxXrGW
    clJU6kjo33Oww3KTEyvQXaFyKbGUzUSa7j6KrY8JWiysHxmve6E1SA/3IG1P
    GipoUj9NgXC0bmD9pyiZSdBUUnM9RF9tvOYKvZGsFnPSOfnfPcg9st7x0W+I
    vDt75F775/s3dAYB5VwdVP7igNuxlomdN4iYK5ePq0KEPc2dB4BUgTwZba2/
    ZWdIyVuHsCxypy2qSDezPDz1z746DrRPSSDtWoiMJg1AKd/8sW6Fm9P+o7KD
    GRhkGAcOHsKGvNzn0pqgN7ksOjfVQGDA92bpppLJs9bKnWNGr5DtqTzRdMzj
    tkgvgstw343oOT7LdOZ0diJaPm/5JVaJPN+P/4IfLCMNX6K5xUs1Tntc1OTG
    UpGEudg2q5WDazolOeRK6l2opVLjJw7y5w+2e9sRlVTrjLmmd63/gHCjs2++
    MbKiwmUV3/72oaMyQzj++KaCmy3itTk6xrLomG58iqdar+vWuDwZ0nQBSzlG
    smNRobQBLEFN3Qx6eL+Ql9Gi4ozz6gzAUtF9V34++hR9YnndnqacbrVO6tIy
    Afvr6slTigEpfsXtKnb0hpl7WU3jVkQlNK/nYZ1KPbKExR5ca04wfs+eK/Sa
    6SQ72AcqpAMvTAKNJzMonT9SKWIr3A==
    =FZa7
    -----END PGP MESSAGE-----
    EOD;

    private const INVALID_GPG_MESSAGE = <<<EOD
    -----BEGIN PGP MESSAGE-----
    TEST
    -----END PGP MESSAGE-----
    EOD;

    public function setUp(): void
    {
        parent::setUp();
        $this->gpg = OpenPGPBackendFactory::get();
    }

    public function testGpgAuthenticatorTraitAssertGpgMessageIsValid_Success()
    {
        $this->assertNull($this->assertGpgMessageIsValid(
            $this->gpg,
            GpgAuthenticatorTraitTest::VALID_GPG_MESSAGE,
            'A valid GPG message should not throw an exception'
        ));
    }

    public function testGpgAuthenticatorTraitAssertGpgMessageIsValid_EmptyError()
    {
        $this->expectException(BadRequestException::class);
        $this->assertGpgMessageIsValid($this->gpg, null, 'Cannot be null');
    }

    public function testGpgAuthenticatorTraitAssertGpgMessageIsValid_NotStringError()
    {
        $this->expectException(BadRequestException::class);
        $this->assertGpgMessageIsValid($this->gpg, [], 'Must be of string type');
    }

    public function testGpgAuthenticatorTraitAssertGpgMessageIsValid_NotOpenpgpMessageError()
    {
        $this->expectException(BadRequestException::class);
        $this->assertGpgMessageIsValid(
            $this->gpg,
            GpgAuthenticatorTraitTest::INVALID_GPG_MESSAGE,
            'Cannot be invalid GPG message'
        );
    }
}
