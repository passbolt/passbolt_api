Release song: https://youtu.be/s88r_q7oufE

Hey community members,

Prepare for an exciting update! 🥁

Passbolt is thrilled to announce that the v4.3.0 Release Candidate is officially available for testing.

The best part? All you have to do is head to GitHub and dive in! Of course, you have to make sure to follow the steps [here](https://community.passbolt.com/t/passbolt-beta-testing-how-to/7894). As always, your feedback is invaluable, please share and report any issues you come across.

Enjoy the testing journey! ♥️


## [4.3.0-rc.1] - 2023-09-21
### Added
- PB-26092 As an administrator I can configure policy for user passphrase
- PB-25405 As an administrator installing passbolt through the web installer, I should be able to configure authentication method for SMTP
- PB-25977 As an administrator I can truncate all account recovery tables, dropping all user and organisation settings
- PB-25185 As a signed-in user on the browser extension, I want to export my account to configure the Windows application
- PB-25685 As an administrator I can lock the set LDAP settings endpoint
- PB-25944 As an administrator I can define the schema on installation with Postgres
- PB-25497 As an administrator I can disable users (experimental)

### Improved
- PB-25709 Performance optimisation of all the SQL queries retrieving resources tags
- PB-25973 Performance optimisation of the SQL query retrieving tags
- PB-24916 Adjust SSO Azure error to return 400 instead of 500 when testing configuration
- PB-25999 Performance optimisation of update secret process
- PB-26097 Adds cake.po translation files for all languages supported by CakePHP

### Security
- PB-25827 As a user with encrypted message enabled in the email content visibility, I would like to see the gpg message encrypted with my key when a password is updated

### Fixed
- PB-25802 As a user I want to see localized date in my emails
- PB-25500 Fix wording in SSO enabled email
- PB-25863 Fix emails not sent due to message-id header missing

### Maintenance
- PB-25709 Remove unused resources tags indexes
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select provider endpoint
