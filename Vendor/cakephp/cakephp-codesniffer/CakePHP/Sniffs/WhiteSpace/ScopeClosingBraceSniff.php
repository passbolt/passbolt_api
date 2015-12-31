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
 * @since         CakePHP CodeSniffer 0.1.12
 * @license       https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

/**
 * Checks that the closing braces of scopes are aligned correctly.
 *
 */
class CakePHP_Sniffs_WhiteSpace_ScopeClosingBraceSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return PHP_CodeSniffer_Tokens::$scopeOpeners;
	}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
 * @param integer $stackPtr  The position of the current token in the
 *    stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		// If this is an inline condition (ie. there is no scope opener), then
		// return, as this is not a new scope.
		if (isset($tokens[$stackPtr]['scope_closer']) === false) {
			return;
		}

		// We need to actually find the first piece of content on this line,
		// as if this is a method with tokens before it (public, static etc)
		// or an if with an else before it, then we need to start the scope
		// checking from there, rather than the current token.
		$lineStart = ($stackPtr - 1);
		for ($lineStart; $lineStart > 0; $lineStart--) {
			if (strpos($tokens[$lineStart]['content'], $phpcsFile->eolChar) !== false) {
				break;
			}
		}

		// We found a new line, now go forward and find the
		// first non-whitespace token.
		$lineStart = $phpcsFile->findNext(array(T_WHITESPACE, T_OPEN_TAG), ($lineStart + 1), null, true);

		// If the next token is a <?php advance 2 so we get the actual
		// control structure.
		if ($tokens[$lineStart + 1]['code'] === T_OPEN_TAG) {
			$lineStart += 2;
		}

		$startColumn = $tokens[$lineStart]['column'];
		$scopeStart = $tokens[$stackPtr]['scope_opener'];
		$scopeEnd = $tokens[$stackPtr]['scope_closer'];

		// Check that the closing brace is on it's own line.
		$lastContent = $phpcsFile->findPrevious(
			array(T_INLINE_HTML, T_WHITESPACE, T_OPEN_TAG),
			($scopeEnd - 1),
			$scopeStart,
			true
		);
		if ($tokens[$lastContent]['line'] === $tokens[$scopeEnd]['line']) {
			$error = 'Closing brace must be on a line by itself';
			$phpcsFile->addError($error, $scopeEnd, 'ContentBefore');
			return;
		}

		// If the previous token was <?php then backtrack
		// one token.
		if ($tokens[$scopeEnd - 1]['code'] === T_OPEN_TAG) {
			// $scopeEnd -= 1;
		}

		// Check now that the closing brace is lined up correctly.
		$braceIndent = $tokens[$scopeEnd]['column'];
		if (in_array($tokens[$stackPtr]['code'], array(T_CASE, T_DEFAULT)) === false) {
			if ($braceIndent !== $startColumn) {
				$error = 'Closing brace indented incorrectly; expected %s spaces, found %s';
				$data = array(
					($startColumn - 1),
					($braceIndent - 1),
				);
				$phpcsFile->addError($error, $scopeEnd, 'Indent', $data);
			}
		}
	}

}
