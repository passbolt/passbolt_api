<?php
/**
 * Resource model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Model.Resource
 * @since         version 2.12.7
 */

class AppTestCase extends CakeTestCase {

	public static $masks = array();

	public static function initMasks() {
		self::$masks = array(
			'alphaASCII' => 'abcdefghijklmnopqrstuvwxyz',
			'alphaASCIIUpper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'alphaAccent' => 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ',
			'alphaChinese' => '完善的密碼管理解決方案 為小型公司和企業的商人',	// The perfect password management solution for businesses and small companies
			'alphaArabic' => 'إدارة كلمة المرور الحل المثالي للشركات الصغيرة والشركات رجل الأعمال', // The perfect password management solution for businesses and small companies
			'alphaRussian' => 'Идеальное решение для управления пароль для небольших компаний и предприятий бизнесмена', // The perfect password management solution for businesses and small companies
			'digit' => '0123456789',
			'float' => '3.57',
			'special' => '!@#$%^&*()_-+={}[]:";<>?,./\\|~',
			'null' => null,
			'email' => 'passbolt_team-2012@passbolt_team-2012.com',
			'date' => '01/01/2012',
			'html' => '<h1>La solution gestion de mot de passe</h1> parfaite pour les <b>business</b> et les <span style="font-size:10px">petites</span> entreprises sans oublier les accents <span style="background: url()">indispensables</span> dans l\'alphabet latin ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÚÚÚÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ'
		);
		self::$masks['alphaLatin'] = self::$masks['alphaASCII'] . self::$masks['alphaASCIIUpper'] . self::$masks['alphaAccent'];
	}

	public static function getMask($maskName) {
		if (empty(self::$masks)) {
			self::initMasks();
		}
		return self::$masks[$maskName];
	}

	public static function randString($length, $maskStr) {
		$returnValue = '';
		preg_match_all('/./u', $maskStr, $array);
		$maskArr = $array[0];
		for ($i = 0; $i < $length; $i++) {
			$key = array_rand($maskArr);
			$returnValue .= $maskArr[$key];
		}
		return $returnValue;
	}
}
