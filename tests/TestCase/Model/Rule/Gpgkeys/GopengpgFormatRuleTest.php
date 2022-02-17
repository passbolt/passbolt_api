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
 * @since         3.5.0
 */

namespace App\Test\TestCase\Model\Rule\Gpgkeys;

use App\Model\Entity\Gpgkey;
use App\Model\Rule\Gpgkeys\GopengpgFormatRule;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\TestCase;

class GopengpgFormatRuleTest extends TestCase
{
    use ModelAwareTrait;

    public function testGopengpgFormatRule_Success()
    {
        $gpgkey = new Gpgkey();
        $gpgkey->armored_key = file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key');

        $rule = new GopengpgFormatRule();
        $this->assertTrue($rule($gpgkey));
    }

    /**
     * Returns false if two return lines are found before the end of the key
     */
    public function testGopengpgFormatRule_Error_Double_New_Line()
    {
        $gpgkey = new Gpgkey();
        $gpgkey->armored_key = file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'double_carriage_return_secret_private.key');

        $rule = new GopengpgFormatRule();
        $this->assertFalse($rule($gpgkey));
    }
}
