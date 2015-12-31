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
if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
	$error = 'Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found';
	throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Ensures the throws in the code are declared in the PHPDoc
 *
 */
class CakePHP_Sniffs_Commenting_FunctionCommentThrowTagSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff {

/**
 * Constructs a CakePHP_Sniffs_Commenting_FunctionCommentThrowTagSniff.
 */
	public function __construct() {
		parent::__construct(array(T_FUNCTION), array(T_THROW));
	}

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr The position of the current token in the stack passed in $tokens.
 * @return void
 */
	protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope) {
		// Is this the first throw token within the current function scope?
		// If so, we have to validate other throw tokens within the same scope.
		$previousThrow = $phpcsFile->findPrevious(T_THROW, ($stackPtr - 1), $currScope);
		if ($previousThrow !== false) {
			return;
		}

		// Parse the function comment.
		$tokens = $phpcsFile->getTokens();
		$commentEnd = $phpcsFile->findPrevious(T_DOC_COMMENT, ($currScope - 1));
		$commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
		$comment = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

		try {
			$this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($comment, $phpcsFile);
			$this->commentParser->parse();
		} catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
			$line = ($e->getLineWithinComment() + $commentStart);
			$phpcsFile->addError($e->getMessage(), $line, 'FailedParse');
			return;
		}

		// Find the position where the current function scope ends.
		$currScopeEnd = 0;
		if (isset($tokens[$currScope]['scope_closer']) === true) {
			$currScopeEnd = $tokens[$currScope]['scope_closer'];
		}

		// Find all the exception type token within the current scope.
		$throwTokens = array();
		$currPos = $stackPtr;
		if ($currScopeEnd !== 0) {
			while ($currPos < $currScopeEnd && $currPos !== false) {
				/*
					If we can't find a NEW, we are probably throwing
					a variable, so we ignore it, but they still need to
					provide at least one @throws tag, even through we
					don't know the exception class.
				*/

				$nextToken = $phpcsFile->findNext(T_WHITESPACE, ($currPos + 1), null, true);
				if ($tokens[$nextToken]['code'] === T_NEW) {
					$currException = $phpcsFile->findNext(array(T_STRING, T_NS_SEPARATOR), $currPos, $currScopeEnd, false, null, true);
					if ($currException !== false) {
						$exception = $tokens[$currException]['content'];
						$i = $currException + 1;
						while (in_array($tokens[$i]['code'], array(T_STRING, T_NS_SEPARATOR))) {
							$exception .= $tokens[$i++]['content'];
						}
						$throwTokens[] = $exception;
					}
				}

				$currPos = $phpcsFile->findNext(T_THROW, ($currPos + 1), $currScopeEnd);
			}
		}

		$namespace = $this->_getNamespace($phpcsFile, $currScope);
		$uses = $this->_readUses($phpcsFile);
		$throwTokens = $this->_adjustThrows($throwTokens, $namespace, $uses);

		$throws = $this->commentParser->getThrows();
		if (empty($throws) === true) {
			$error = 'Missing @throws tag in function comment';
			$phpcsFile->addWarning($error, $commentEnd, 'Missing');
		} elseif (empty($throwTokens) === true) {
			// If token count is zero, it means that only variables are being
			// thrown, so we need at least one @throws tag (checked above).
			// Nothing more to do.
			return;
		} else {
			$throwTags = array();
			$lineNumber = array();
			foreach ($throws as $throw) {
				$value = ltrim($throw->getValue(), '\\');
				$throwTags[] = $value;
				$lineNumber[$value] = $throw->getLine();
			}

			$throwTags = array_unique($throwTags);
			sort($throwTags);

			// Make sure @throws tag count matches throw token count.
			$tokenCount = count($throwTokens);
			$tagCount = count($throwTags);
			if ($tokenCount !== $tagCount) {
				$error = 'Expected %s @throws tag(s) in function comment; %s found';
				$data = array(
					$tokenCount,
					$tagCount,
				);
				$phpcsFile->addWarning($error, $commentEnd, 'WrongNumber', $data);
				return;
			} else {
				// Exception type in @throws tag must be thrown in the function.
				foreach ($throwTags as $i => $throwTag) {
					$errorPos = ($commentStart + $lineNumber[$throwTag]);
					if (empty($throwTag) === false && $throwTag !== $throwTokens[$i]) {
						$error = 'Expected "%s" but found "%s" for @throws tag exception';
						$data = array(
							$throwTokens[$i],
							$throwTag,
						);
						$phpcsFile->addWarning($error, $errorPos, 'WrongType', $data);
					}
				}
			}
		}
	}

/**
 * Find the class namespace.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $currScope Current scope
 * @return string
 */
	protected function _getNamespace(PHP_CodeSniffer_File $phpcsFile, $currScope) {
		$nsPos = $phpcsFile->findPrevious(T_NAMESPACE, $currScope - 1);
		if (!$nsPos) {
			return '';
		}

		$tokens = $phpcsFile->getTokens();
		$i = $nsPos + 2; // Ignore whitespace
		$ns = '';
		while (in_array($tokens[$i]['code'], array(T_STRING, T_NS_SEPARATOR))) {
			$ns .= $tokens[$i]['content'];
			$i++;
		}
		return $ns;
	}

/**
 * Read the use declarations
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @return array
 */
	protected function _readUses(PHP_CodeSniffer_File $phpcsFile) {
		$pos = $phpcsFile->findNext(T_USE, 1);
		if (!$pos) {
			return array();
		}

		$tokens = $phpcsFile->getTokens();
		$pos += 2; // Ignore use keywork and whitespace
		$uses = array();
		do {
			$use = $alias = '';
			while (in_array($tokens[$pos]['code'], array(T_STRING, T_NS_SEPARATOR))) {
				$use .= $tokens[$pos]['content'];
				$pos++;
			}

			while (in_array($tokens[$pos]['code'], array(T_WHITESPACE, T_AS))) {
				$pos++;
			}
			if ($tokens[$pos]['code'] === T_STRING) {
				$alias = $tokens[$pos]['content'];
				$pos++;
			}

			if ($tokens[$pos]['code'] === T_COMMA) {
				$pos++;
				if ($tokens[$pos]['code'] === T_WHITESPACE) {
					$pos++;
				}
			} else { // End of uses
				$pos = $phpcsFile->findNext(T_USE, $pos);
				if ($pos) {
					$pos += 2; // Ignore use keywork and whitespace
				}
			}

			if (!$alias) {
				$alias = basename(str_replace('\\', '/', $use));
			}

			$uses[$alias] = $use;
		} while ($pos);
		return $uses;
	}

/**
 * Adjust the throw to use the namespace or aliases names
 *
 * @param array $throws
 * @param string $namespace
 * @param array $uses
 * @return array
 */
	protected function _adjustThrows($throws, $namespace, $uses) {
		$formatted = array();
		foreach ($throws as $throw) {
			if ($throw[0] === '\\') { // Global
				$formatted[] = substr($throw, 1);
				continue;
			}

			$basename = $throw;
			$complement = '';
			if (strpos($basename, '\\') !== false) {
				list($basename, $complement) = explode('\\', $basename, 2);
			}

			if (isset($uses[$basename])) {
				$formatted[] = trim($uses[$basename] . '\\' . $complement, '\\');
				continue;
			}

			$formatted[] = trim($namespace . '\\' . $throw, '\\');
		}

		// Only need one @throws tag for each type of exception thrown.
		$throws = array_unique($formatted);
		sort($throws);
		return $throws;
	}

}
