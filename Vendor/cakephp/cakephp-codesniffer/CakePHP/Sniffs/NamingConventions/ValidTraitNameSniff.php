<?php
/**
 * PHP Version 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://pear.php.net/package/PHP_CodeSniffer_CakePHP
 * @since         CakePHP CodeSniffer 0.1.10
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Ensures trait names are correct depending on the folder of the file.
 *
 */
class CakePHP_Sniffs_NamingConventions_ValidTraitNameSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * If the constant is not defined, ignore because probably the PHP version
 * is under 5.4.0 and don't have traits in use
 *
 * @return array
 */
	public function register() {
		if (!defined('T_TRAIT')) {
			return array();
		}
		return array(T_TRAIT);
	}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr  The position of the current token in the stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$traitName = $tokens[$stackPtr + 2]['content'];

		if (substr($traitName, -5) !== 'Trait') {
			$error = 'Traits must have a "Trait" suffix.';
			$phpcsFile->addError($error, $stackPtr, 'InvalidTraitName', array());
		}
	}

}
