<?php
/**
 * PHP Version 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * This file is originally written by Greg Sherwood and Marc McIntyre, but
 * modified for CakePHP.
 *
 * @copyright     2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @link          http://pear.php.net/package/PHP_CodeSniffer_CakePHP
 * @since         CakePHP CodeSniffer 0.1.1
 * @license       https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

/**
 * Checks that there is no empty line after the opening brace of a function.
 *
 */
class CakePHP_Sniffs_WhiteSpace_FunctionOpeningBraceSpaceSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return array(T_FUNCTION);
	}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr  The position of the current token
 *   in the stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		if (isset($tokens[$stackPtr]['scope_opener']) === false) {
			// Probably an interface method.
			return;
		}

		$openBrace = $tokens[$stackPtr]['scope_opener'];
		$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($openBrace + 1), null, true);

		if ($nextContent === $tokens[$stackPtr]['scope_closer']) {
			// The next bit of content is the closing brace, so this
			// is an empty function and should have a blank line
			// between the opening and closing braces.
			return;
		}

		$braceLine = $tokens[$openBrace]['line'];
		$nextLine = $tokens[$nextContent]['line'];

		$found = ($nextLine - $braceLine - 1);
		if ($found > 0) {
			$error = 'Expected 0 blank lines after opening function brace; %s found';
			$data = array($found);
			$phpcsFile->addError($error, $openBrace, 'SpacingAfter', $data);
		}
	}

}
