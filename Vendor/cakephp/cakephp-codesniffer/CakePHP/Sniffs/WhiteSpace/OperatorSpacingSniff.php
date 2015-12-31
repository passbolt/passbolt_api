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
 * @since         CakePHP CodeSniffer 0.1.6
 * @license       https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

/**
 * Verifies that operators have valid spacing surrounding them.
 *
 */
class CakePHP_Sniffs_WhiteSpace_OperatorSpacingSniff implements PHP_CodeSniffer_Sniff {

/**
 * A list of tokenizers this sniff supports.
 *
 * @var array
 */
	public $supportedTokenizers = array(
		'PHP',
		'JS',
	);

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		$comparison = PHP_CodeSniffer_Tokens::$comparisonTokens;
		$operators = PHP_CodeSniffer_Tokens::$operators;
		$assignment = PHP_CodeSniffer_Tokens::$assignmentTokens;

		return array_unique(array_merge($comparison, $operators, $assignment));
	}

/**
 * Processes this sniff, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The current file being checked.
 * @param integer $stackPtr  The position of the current token in the
 *    stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		// Skip default values in function declarations.
		if ($tokens[$stackPtr]['code'] === T_EQUAL
			|| $tokens[$stackPtr]['code'] === T_MINUS
		) {
			if (isset($tokens[$stackPtr]['nested_parenthesis']) === true) {
				$parenthesis = array_keys($tokens[$stackPtr]['nested_parenthesis']);
				$bracket = array_pop($parenthesis);
				if (isset($tokens[$bracket]['parenthesis_owner']) === true) {
					$function = $tokens[$bracket]['parenthesis_owner'];
					if ($tokens[$function]['code'] === T_FUNCTION) {
						return;
					}
				}
			}
		}

		if ($tokens[$stackPtr]['code'] === T_EQUAL) {
			// Skip for '=&' case.
			if (isset($tokens[($stackPtr + 1)]) === true && $tokens[($stackPtr + 1)]['code'] === T_BITWISE_AND) {
				return;
			}
		}

		if ($tokens[$stackPtr]['code'] === T_BITWISE_AND) {
			// If its not a reference, then we expect one space either side of the
			// bitwise operator.
			if (!$phpcsFile->isReference($stackPtr) && !$this->_isVariable($stackPtr, $tokens, $phpcsFile)) {
				// Check there is one space before the & operator.
				if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
					$error = 'Expected 1 space before "&" operator; 0 found';
					$phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeAmp');
				}

				// Check there is one space after the & operator.
				if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
					$error = 'Expected 1 space after "&" operator; 0 found';
					$phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterAmp');
				}
			}
		} else {
			if ($tokens[$stackPtr]['code'] === T_MINUS) {
				// Skip declaration of negative value in new array format; eg. $arr = [-1].
				if ($tokens[($stackPtr - 1)]['code'] === T_OPEN_SHORT_ARRAY) {
					return;
				}

				// Check minus spacing, but make sure we aren't just assigning
				// a minus value or returning one.
				$prev = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
				if ($tokens[$prev]['code'] === T_RETURN) {
					// Just returning a negative value; eg. return -1.
					return;
				}

				if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$operators) === true) {
					// Just trying to operate on a negative value; eg. ($var * -1).
					return;
				}

				if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens) === true) {
					// Just trying to compare a negative value; eg. ($var === -1).
					return;
				}

				// A list of tokens that indicate that the token is not
				// part of an arithmetic operation.
				$invalidTokens = array(
					T_COMMA,
					T_OPEN_PARENTHESIS,
					T_OPEN_SQUARE_BRACKET,
					T_DOUBLE_ARROW,
					T_COLON,
					T_INLINE_THEN,
					T_INLINE_ELSE,
					T_CASE,
				);

				if (in_array($tokens[$prev]['code'], $invalidTokens) === true) {
					// Just trying to use a negative value; eg. myFunction($var, -2).
					return;
				}
				if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens) === true) {
					// Just trying to assign a negative value; eg. ($var = -1).
					return;
				}
			}

			$operator = $tokens[$stackPtr]['content'];

			if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
				$error = "Expected 1 space before \"$operator\"; 0 found";
				$phpcsFile->addError($error, $stackPtr, 'NoSpaceBefore');
			}

			if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
				$error = "Expected 1 space after \"$operator\"; 0 found";
				$phpcsFile->addError($error, $stackPtr, 'NoSpaceAfter');
			}
		}
	}

/**
 * Check if the current token is inside an array.
 *
 * @param int $stackPtr The current token offset.
 * @param array $phpcsFile The current token list.
 * @return bool
 */
	protected function _isVariable($stackPtr, $tokens, $phpcsFile) {
		$tokenAfter = $phpcsFile->findNext(
			PHP_CodeSniffer_Tokens::$emptyTokens,
			($stackPtr + 1),
			null,
			true
		);
		$tokenBefore = $phpcsFile->findNext(
			PHP_CodeSniffer_Tokens::$emptyTokens,
			($stackPtr - 1),
			null,
			true
		);

		if ($tokens[$tokenAfter]['code'] === T_VARIABLE &&
			(
				$tokens[$tokenBefore]['code'] === T_OPEN_PARENTHESIS ||
				$tokens[$tokenBefore]['code'] === T_COMMA ||
				$tokens[$tokenBefore]['code'] === T_OPEN_SHORT_ARRAY
			)
		) {
			return true;
		}
		return false;
	}

}
