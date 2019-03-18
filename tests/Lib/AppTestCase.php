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
 * @since         2.0.0
 */
namespace App\Test\Lib;

use App\Test\Lib\Model\AvatarsModelTrait;
use App\Test\Lib\Model\CommentsModelTrait;
use App\Test\Lib\Model\FavoritesModelTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Test\Lib\Utility\ArrayTrait;
use App\Test\Lib\Utility\EntityTrait;
use App\Test\Lib\Utility\ObjectTrait;
use Cake\TestSuite\TestCase;

abstract class AppTestCase extends TestCase
{
    // Do not load all the traits here
    // load them were needed instead
    use ArrayTrait;
    use CommentsModelTrait;
    use EntityTrait;
    use FavoritesModelTrait;
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use ObjectTrait;
    use PermissionsModelTrait;
    use ProfilesModelTrait;
    use ResourcesModelTrait;
    use SecretsModelTrait;
    use UsersModelTrait;

    public static $stringMasks = [];

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Initialize string masks.
     *
     * The string masks are UTF8 strings containing various languages and emojis, and that can be used for testing.
     */
    public static function initStringMasks()
    {
        self::$stringMasks = [
            'alphaASCII' => 'abcdefghijklmnopqrstuvwxyz',
            'alphaASCIIUpper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alphaAccent' => 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ',
            'alphaChinese' => '完善的密碼管理解決方案 為小型公司和企業的商人', // The perfect password management solution for businesses and small companies
            'alphaArabic' => 'إدارة كلمة المرور الحل المثالي للشركات الصغيرة والشركات رجل الأعمال', // The perfect password management solution for businesses and small companies
            'alphaRussian' => 'Идеальное решение для управления пароль для небольших компаний и предприятий бизнесмена', // The perfect password management solution for businesses and small companies
            'alphaEmojis' => '', // List of smileys.
            'special' => '!@#$%^&*()_-+={}[]:";<>?,./\\|~ ',
            'html' => '<h1>La solution gestion de mot de passe</h1> parfaite pour les <b>business</b> et les <span style="font-size:10px">petites</span> entreprises sans oublier les accents <span style="background: url()">indispensables</span> dans l\'alphabet latin ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ'
        ];

        // Init emojis. (Covers all common smileys: 1F601 - 1F64F)
        $values = [
            "\u{1F601}", "\u{1F602}", "\u{1F603}", "\u{1F604}", "\u{1F605}", "\u{1F606}", "\u{1F609}",
            "\u{1F60A}", "\u{1F60B}", "\u{1F60C}", "\u{1F60D}", "\u{1F60F}", "\u{1F612}", "\u{1F613}",
            "\u{1F614}", "\u{1F616}", "\u{1F618}", "\u{1F61A}", "\u{1F61C}", "\u{1F61D}", "\u{1F61E}",
            "\u{1F620}", "\u{1F621}", "\u{1F622}", "\u{1F623}", "\u{1F624}", "\u{1F625}", "\u{1F628}",
            "\u{1F629}", "\u{1F62A}", "\u{1F62B}", "\u{1F62D}", "\u{1F630}", "\u{1F631}", "\u{1F632}",
            "\u{1F633}", "\u{1F635}", "\u{1F637}", "\u{1F638}", "\u{1F639}", "\u{1F63A}", "\u{1F63B}",
            "\u{1F63C}", "\u{1F63D}", "\u{1F63E}", "\u{1F63F}", "\u{1F640}", "\u{1F645}", "\u{1F646}",
            "\u{1F647}", "\u{1F648}", "\u{1F649}", "\u{1F64A}", "\u{1F64B}", "\u{1F64C}", "\u{1F64D}",
            "\u{1F64E}", "\u{1F64F}",
        ];
        foreach ($values as $value) {
            self::$stringMasks['alphaEmojis'] .= \IntlChar::chr($value);
        }

        // Init alphaLatin.
        self::$stringMasks['alphaLatin'] = self::$stringMasks['alphaASCII'] . self::$stringMasks['alphaASCIIUpper'] . self::$stringMasks['alphaAccent'];
    }

    /**
     * Get a string mask.
     *
     * @param string $maskName name of the mask to use.
     * @param null $length length of the string to return.
     * @return string an utf8mb4 string of the size mentioned in length parameter.
     */
    public static function getStringMask($maskName, $length = null)
    {
        if (empty(self::$stringMasks)) {
            self::initStringMasks();
        }
        if (!isset(self::$stringMasks[$maskName])) {
            throw new \OutOfBoundsException('The requested mask doesn\'t exist');
        }
        if ($length == null) {
            return self::$stringMasks[$maskName];
        }

        $mask = self::mbStrPad(self::$stringMasks[$maskName], $length, self::$stringMasks[$maskName]);

        return mb_substr($mask, 0, $length);
    }

    /**
     * Custom implementation of mb_str_pad, with full unicode support.
     * Initially php doesn't support extended UTF-8 in str_pad. (see: http://php.net/manual/en/ref.mbstring.php#90611)
     * Working implementation was found here: https://stackoverflow.com/questions/14773072/php-str-pad-unicode-issue
     * @param $str
     * @param $pad_len
     * @param string $pad_str
     * @param int $dir
     * @param null $encoding
     *
     * @return string
     */
    public static function mbStrPad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = null)
    {
        $encoding = $encoding === null ? mb_internal_encoding() : $encoding;
        $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
        $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
        $pad_len -= mb_strlen($str, $encoding);
        $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
        $strToRepeatLen = mb_strlen($pad_str, $encoding);
        $repeatTimes = ceil($targetLen / $strToRepeatLen);
        $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid unicode sequences (any charset)
        $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
        $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';

        return $before . $str . $after;
    }
}
