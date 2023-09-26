Release song: https://youtu.be/s88r_q7oufE

The latest version of Pro is here – take a look at what’s new in 4.3.

One enhancement is improved portability of TOTP (Time Based One Time Password). TOTP can now be conveniently viewed across both the web and mobile applications. Although the creation of TOTP remains mobile-centric, version 4.3 provides convenient access to reading and retrieving TOTP content in the browser, resulting in greater usability.

Improvements have also been made to grid customisation. Any changes made to the grid are now persistent, meaning your tailored experience is saved from session to session. And to make the new TOTP portability even more accessible, an option has been added to display a column for your TOTP content.

Admins can now manage passphrase policies alongside their password policies. These policies include: setting minimal entropy, managing access to external tools for monitoring if a passphrase has been compromised, and choosing to enforce policies for existing users.

Other updates include improvements to SQL query performance (retrieving resource tags and system tags), restricting LDAP-related settings, some bug fixes, and a number of performance improvements.

Thank you for choosing passbolt and for your continued support.


## [4.3.0] - 2023-09-26
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
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8

### Maintenance
- PB-25709 Remove unused resources tags indexes
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select provider endpoint
