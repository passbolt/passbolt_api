# Changelog

## 2.0.6

 - Added Deserializer for converting json string into a Swagger Annotations object. #290
 - Various readme improvements #284 #286
 - Various PSR-2 coding standard fixes #291
 - Travis now enforces passing PHP CodeSniffer PSR-2 tests #291
 - Use badges from shields.io #288

## 2.0.5
 - Removed JSON-Schema properties that are not supported in swagger. #273
 - Added ordering filenames to guarantee a consistent output #263

## 2.0.4
 - Fixed minor regression #254
 - Removed format restrictions from the remaining annotations #253

## 2.0.3

- Removed format restrictions #253
- Fixed a bug with inherited properties #250

## 2.0.2

- Added support for @SWG\Head & @SWG\Options for documenting the HEAD and OPTIONS responses
- Removed restrictions in @SWG\Items, allow @SWG\Property and other @SWG\Schema options.

## 2.0.1

- Minimal support for traits (no inheritance)  

## 2.0.0

- Generates swagger.json with Swagger Specification v2
- New simplified architecture (crawl -> analysis -> processors -> validation -> output)
