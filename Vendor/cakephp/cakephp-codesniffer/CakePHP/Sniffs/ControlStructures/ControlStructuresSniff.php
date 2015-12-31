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
 * @since         CakePHP CodeSniffer 0.1.14
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Ensures curly brackets are used with if, else, elseif, foreach and for.
 * while and dowhile are covered elsewhere
 *
 */
class CakePHP_Sniffs_ControlStructures_ControlStructuresSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return array(T_IF, T_ELSEIF, T_ELSE, T_FOREACH, T_FOR);
	}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * Checks that curly brackets are used with if, else, elseif, foreach and for.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer              $stackPtr  The position of the current token in the
 *                                        stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		$nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
		if ($tokens[$nextToken]['code'] === T_OPEN_PARENTHESIS) {
			$closer = $tokens[$nextToken]['parenthesis_closer'];
			$diff = $closer - $stackPtr;
			$nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + $diff + 1), null, true);
		}
		if ($tokens[$nextToken]['code'] === T_IF) {
			// "else if" is not checked by this sniff, another sniff takes care of that.
			return;
		}
		if ($tokens[$nextToken]['code'] !== T_OPEN_CURLY_BRACKET && $tokens[$nextToken]['code'] !== T_COLON) {
			$error = 'Curly brackets required for if/elseif/else.';
			$phpcsFile->addError($error, $stackPtr, 'NotAllowed');
		}
	}

}
