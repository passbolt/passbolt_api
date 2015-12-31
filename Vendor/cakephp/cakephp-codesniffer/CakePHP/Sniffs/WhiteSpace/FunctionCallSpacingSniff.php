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
 * @since         CakePHP CodeSniffer 0.1.12
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Checks the separation between methods in a class or interface.
 *
 */
class CakePHP_Sniffs_WhiteSpace_FunctionCallSpacingSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return array(
			T_ISSET,
			T_EMPTY,
			T_STRING,
		);
	}

/**
 * Processes this sniff, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr The position of the current token in the stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		// Find the next non-empty token.
		$openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);

		if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
			// Not a function call.
			return;
		}

		// Look for funcName (
		if (($stackPtr + 1) !== $openBracket) {
			$error = 'Space before opening parenthesis of function call not allowed';
			$phpcsFile->addError($error, $stackPtr, 'SpaceBeforeOpenBracket');
		}
	}

}
