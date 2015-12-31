# CakePHP Code Sniffer [![Build Status](https://travis-ci.org/cakephp/cakephp-codesniffer.png?branch=master)](http://travis-ci.org/cakephp/cakephp-codesniffer)

This code works with [phpcs](http://pear.php.net/manual/en/package.php.php-codesniffer.php)
and checks code against the coding standards used in CakePHP.

## Installation

It's generally recommended to install these code sniffs with the PEAR
installer:

	pear channel-discover pear.cakephp.org
	pear install cakephp/CakePHP_CodeSniffer

You can also install the code sniffs with `composer`:

	php composer.phar require cakephp/cakephp-codesniffer
	vendor/bin/phpcs --config-set installed_paths vendor/cakephp/cakephp-codesniffer

The second command lets `phpcs` know where to find your new sniffs. Ensure that
you do not overwrite any existing `installed_paths` value.

For CakePHP 3.x apps, the default composer bin is at root of your app, so replace `vendor/bin/` by `bin/` in all commands.

## Usage

Depending on how you installed the code sniffer changes how you run it. If you have
installed phpcs, and this package with PEAR, you can do the following:

	phpcs --standard=CakePHP /path/to/code

*Warning* when these sniffs are installed with composer, ensure that you have
configured the CodeSniffer `installed_paths` setting.

Once `installed_paths` is configured, you can run phpcs using:

	vendor/bin/phpcs --standard=CakePHP

## Contributing

If you'd like to contribute to the Code Sniffer, you can fork the project add features and send pull requests.

Make sure to clone the repository to something like **cakephp_codesniffer** (instead of the default **cakephp-codesniffer**) because otherwise `phpunit` will fail to run the tests.

## Releasing CakePHP Code Sniffer

* Update version number in build.xml
* Add changelog entry.
* Commit changes.
* Create git tag.
* Run `phing release`
