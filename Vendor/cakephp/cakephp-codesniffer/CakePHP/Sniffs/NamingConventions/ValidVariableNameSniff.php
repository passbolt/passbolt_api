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
 * @since         CakePHP CodeSniffer 0.1.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
	throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found');
}

/**
 * Checks the naming of variables and member variables.
 *
 */
class CakePHP_Sniffs_NamingConventions_ValidVariableNameSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff {

/**
 * Processes this test, when one of its tokens is encountered.
 *
 * Processes variables, we skip processing object properties because
 * they could come from things like PDO which doesn't follow the normal
 * conventions and causes additional failures.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr  The position of the current token in the
 *    stack passed in $tokens.
 * @return void
 */
	protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$varName = ltrim($tokens[$stackPtr]['content'], '$');

		$phpReservedVars = array(
			'_SERVER',
			'_GET',
			'_POST',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'_FILES',
			'GLOBALS',
		);

		// If it's a php reserved var, then its ok.
		if (in_array($varName, $phpReservedVars) === true) {
			return;
		}

		// There is no way for us to know if the var is public or private,
		// so we have to ignore a leading underscore if there is one and just
		// check the main part of the variable name.
		$originalVarName = $varName;
		if (substr($varName, 0, 1) === '_') {
			$objOperator = $phpcsFile->findPrevious(array(T_WHITESPACE), ($stackPtr - 1), null, true);
			if ($tokens[$objOperator]['code'] === T_DOUBLE_COLON) {
				// The variable lives within a class, and is referenced like
				// this: MyClass::$_variable, so we don't know its scope.
				$inClass = true;
			} else {
				$inClass = $phpcsFile->hasCondition($stackPtr, array(T_TRAIT, T_CLASS, T_INTERFACE));
			}

			if ($inClass === true) {
				$varName = ltrim($varName, '_');
			}
		}

		// $title_for_layout is allowed on controllers
		$fileName = basename($phpcsFile->getFilename(), '.php');
		if ((substr($fileName, -10) === 'Controller') && ($varName == 'title_for_layout')) {
			return;
		}

		if ($this->_isValidVar($varName) === false) {
			$error = 'Variable "%s" is not in valid camel caps format';
			$data = array($originalVarName);
			$phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
		}
	}

/**
 * Processes class member variables.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr  The position of the current token in the
 *    stack passed in $tokens.
 * @return void
 */
	protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		$varName = ltrim($tokens[$stackPtr]['content'], '$');
		$memberProps = $phpcsFile->getMemberProperties($stackPtr);
		$public = ($memberProps['scope'] === 'public');
		$private = ($memberProps['scope'] === 'private');

		if ($public === true) {
			if (substr($varName, 0, 1) === '_') {
				$error = 'Public member variable "%s" must not contain a leading underscore';
				$data = array($varName);
				$phpcsFile->addError($error, $stackPtr, 'PublicHasUnderscore', $data);
				return;
			}
		} elseif ($private === true) {
			if (substr($varName, 0, 2) !== '__') {
				$error = 'Private member variable "%s" must contain a leading underscore';
				$data = array($varName);
				$phpcsFile->addError($error, $stackPtr, 'PrivateNoUnderscore', $data);
				return;
			}
			$filename = $phpcsFile->getFilename();
			if (strpos($filename, '/lib/Cake/') !== false) {
				$warning = 'Private variable "%s" in CakePHP core is discouraged';
				$data = array($varName);
				$phpcsFile->addWarning($warning, $stackPtr, 'PrivateInCore', $data);
			}
		} else {
			if (substr($varName, 0, 1) !== '_') {
				$error = 'Protected member variable "%s" must contain a leading underscore';
				$data = array($varName);
				$phpcsFile->addError($error, $stackPtr, 'ProtectedNoUnderscore', $data);
				return;
			}
		}

		$conditions = array_keys($tokens[$stackPtr]['conditions']);
		$className = $phpcsFile->getDeclarationName(array_pop($conditions));

		// Schema properties are allowed to not be CamelCase.
		if (substr($className, -6) === 'Schema') {
			return;
		}

		if ($this->_isValidVar($varName, $public) === false) {
			$error = 'Member variable "%s" is not in valid camel caps format';
			$data = array($varName);
			$phpcsFile->addError($error, $stackPtr, 'MemberVarNotCamelCaps', $data);
		}
	}

/**
 * Processes the variable found within a double quoted string.
 *
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr The position of the double quoted string.
 * @return void
 */
	protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		$phpReservedVars = array(
			'_SERVER',
			'_GET',
			'_POST',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'_FILES',
			'GLOBALS',
		);

		if (preg_match_all('|[^\\\]\$([a-zA-Z0-9_]+)|', $tokens[$stackPtr]['content'], $matches) !== 0) {
			foreach ($matches[1] as $varName) {
				// If it's a php reserved var, then its ok.
				if (in_array($varName, $phpReservedVars) === true) {
					continue;
				}

				// There is no way for us to know if the var is public or private,
				// so we have to ignore a leading underscore if there is one and just
				// check the main part of the variable name.
				$originalVarName = $varName;
				if (substr($varName, 0, 1) === '_') {
					if ($phpcsFile->hasCondition($stackPtr, array(T_CLASS, T_INTERFACE)) === true) {
						$varName = substr($varName, 1);
					}
				}

				if ($this->_isValidVar($varName) === false) {
					$error = 'Variable "%s" is not in valid camel caps format';
					$data = array($originalVarName);
					$phpcsFile->addError($error, $stackPtr, 'StringVarNotCamelCaps', $data);
				}
			}
		}
	}

/**
 * Check that a variable is a valid shape.
 *
 * Variables in CakePHP can either be $fooBar, $FooBar, $_fooBar, or $_FooBar.
 *
 * @param string $string The variable to check.
 * @param boolea $public Whether or not the variable is public.
 * @return boolean
 */
	protected function _isValidVar($string, $public = true) {
		$firstChar = '[a-zA-Z]';
		if (!$public) {
			$firstChar = '[_]{1,2}' . $firstChar;
		}
		if (preg_match("|^$firstChar|", $string) === 0) {
			return false;
		}
		$firstStringCount = 1;
		if (preg_match("|^__|", $string)) {
			$firstStringCount = 2;
		}
		// Check that the name only contains legal characters.
		$legalChars = 'a-zA-Z0-9';
		if (preg_match("|[^$legalChars]|", substr($string, $firstStringCount)) > 0) {
			return false;
		}
		return true;
	}

}
