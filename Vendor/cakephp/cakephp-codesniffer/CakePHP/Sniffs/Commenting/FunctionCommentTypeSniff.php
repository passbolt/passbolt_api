<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://pear.php.net/package/PHP_CodeSniffer_CakePHP
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Asserts that function comments use the short form for types:
 * - bool instead of boolean
 * - int instead of integer
 */
class CakePHP_Sniffs_Commenting_FunctionCommentTypeSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_DOC_COMMENT);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token
	 * in the stack passed in $tokens.
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		// We are only interested in function/class/interface doc block comments.
		$nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
		$ignore = array(
			T_CLASS,
			T_INTERFACE,
			T_FUNCTION,
			T_PUBLIC,
			T_PRIVATE,
			T_PROTECTED,
			T_STATIC,
			T_ABSTRACT,
		);

		if (in_array($tokens[$nextToken]['code'], $ignore) === false) {
			// Could be a file comment.
			$prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
			if ($tokens[$prevToken]['code'] !== T_OPEN_TAG) {
				return;
			}
		}

		$types = array(
			'boolean' => 'bool',
			'integer' => 'int',
		);
		foreach ($types as $from => $to) {
			$this->_check($phpcsFile, $stackPtr, $from, $to);
		}
	}

	/**
	 * MyCakePHP_Sniffs_Commenting_DocBlockTypeSniff::_check()
	 *
	 * @param int $stackPtr
	 * @param string $from
	 * @param string $to
	 * @return void
	 */
	protected function _check(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $from, $to) {
		$tokens = $phpcsFile->getTokens();
		$content = $tokens[$stackPtr]['content'];

		$matches = array();
		if (preg_match('/\@(\w+)\s+([\w\\|\\\\]*?)' . $from . '\b/i', $content, $matches) === 0) {
			return;
		}

		$error = 'Please use "' . $to . '" instead of "' . $from . '" for types in doc blocks.';
		$phpcsFile->addWarning($error, $stackPtr, 'WrongType');
	}

}
