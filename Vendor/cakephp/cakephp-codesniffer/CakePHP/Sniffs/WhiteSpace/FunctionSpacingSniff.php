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
 * Checks the separation between methods in a class or interface.
 *
 */
class CakePHP_Sniffs_WhiteSpace_FunctionSpacingSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return array(T_FUNCTION);
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

		/*
			Check the number of blank lines
			after the function.
		*/

		if (isset($tokens[$stackPtr]['scope_closer']) === false) {
			// Must be an interface method, so the closer is the semi-colon.
			$closer = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
		} else {
			$closer = $tokens[$stackPtr]['scope_closer'];
		}

		// There needs to be 1 blank lines after the closer.
		$nextLineToken = null;
		for ($i = $closer; $i < $phpcsFile->numTokens; $i++) {
			if (strpos($tokens[$i]['content'], $phpcsFile->eolChar) === false) {
				continue;
			} else {
				$nextLineToken = ($i + 1);
				break;
			}
		}

		if ($nextLineToken === null) {
			// Never found the next line, which means
			// there are 0 blank lines after the function.
			$foundLines = 0;
		} else {
			$nextContent = $phpcsFile->findNext(array(T_WHITESPACE), ($nextLineToken + 1), null, true);
			if ($nextContent === false) {
				// We are at the end of the file. That is acceptable as well.
				$foundLines = 1;
			} else {
				$foundLines = ($tokens[$nextContent]['line'] - $tokens[$nextLineToken]['line']);
			}
		}

		if ($foundLines !== 1) {
			$error = 'Expected 1 blank lines after function; %s found';
			$data = array($foundLines);
			$phpcsFile->addError($error, $closer, 'After', $data);
		}

		/*
			Check the number of blank lines
			before the function.
		*/

		$prevLineToken = null;
		for ($i = $stackPtr; $i > 0; $i--) {
			if (strpos($tokens[$i]['content'], $phpcsFile->eolChar) === false) {
				continue;
			} else {
				$prevLineToken = $i;
				break;
			}
		}

		if ($prevLineToken === null) {
			// Never found the previous line, which means
			// there are 0 blank lines before the function.
			$foundLines = 0;
		} else {
			$prevContent = $phpcsFile->findPrevious(array(T_WHITESPACE, T_DOC_COMMENT), $prevLineToken, null, true);

			// Before we throw an error, check that we are not throwing an error
			// for another function. We don't want to error for no blank lines after
			// the previous function and no blank lines before this one as well.
			$currentLine = $tokens[$stackPtr]['line'];
			$prevLine = ($tokens[$prevContent]['line'] - 1);
			$i = ($stackPtr - 1);
			$foundLines = 0;
			while ($currentLine !== $prevLine && $currentLine > 1 && $i > 0) {
				if (isset($tokens[$i]['scope_condition']) === true) {
					$scopeCondition = $tokens[$i]['scope_condition'];
					if ($tokens[$scopeCondition]['code'] === T_FUNCTION) {
						// Found a previous function.
						return;
					}
				} elseif ($tokens[$i]['code'] === T_FUNCTION) {
					// Found another interface function.
					return;
				}

				$currentLine = $tokens[$i]['line'];
				if ($currentLine === $prevLine) {
					break;
				}

				if ($tokens[($i - 1)]['line'] < $currentLine && $tokens[($i + 1)]['line'] > $currentLine) {
					// This token is on a line by itself. If it is whitespace, the line is empty.
					if ($tokens[$i]['code'] === T_WHITESPACE) {
						$foundLines++;
					}
				}
				$i--;
			}
		}

		if ($foundLines !== 1) {
			$error = 'Expected 1 blank lines before function; %s found';
			$data = array($foundLines);
			$phpcsFile->addError($error, $stackPtr, 'Before', $data);
		}
	}

}
