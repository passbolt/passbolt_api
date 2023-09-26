Release song: https://youtu.be/s88r_q7oufE

Introducing the newest release of passbolt – get to know version 4.3

This update extends the portability of TOTP (Time Based One Time Password) content. You can now access TOTP items from passbolt’s mobile app and web interface. While the ability to create a TOTP is still limited to mobile, this update lets you view them through the browser, adding to its flexibility and usability.

Improvements have also been made to the customisation of the grid in the password workspace. This update makes edits to the grid persistent, meaning that changes will now be saved between sessions. To further improve overall usability, an optional column for TOTP has also been added.

Thank you for using passbolt, for contributing to the vision, and your feedback.

## [4.3.0] - 2023-09-26
### Added
- PB-25405 As an administrator installing passbolt through the web installer, I should be able to configure authentication method for SMTP
- PB-25185 As a signed-in user on the browser extension, I want to export my account to configure the Windows application
- PB-25944 As an administrator I can define the schema on installation with Postgres
- PB-25497 As an administrator I can disable users (experimental)

### Improved
- PB-25999 Performance optimisation of update secret process
- PB-26097 Adds cake.po translation files for all languages supported by CakePHP

### Security
- PB-25827 As a user with encrypted message enabled in the email content visibility, I would like to see the gpg message encrypted with my key when a password is updated

### Fixed
- PB-25802 As a user I want to see localized date in my emails
- PB-25863 Fix emails not sent due to message-id header missing
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8

### Maintenance
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select provider endpoint
