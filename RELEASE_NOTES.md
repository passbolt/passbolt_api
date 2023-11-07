Release song: https://www.youtube.com/watch?v=6Ejga4kJUts

Version 4.4 of Passbolt Pro is now available, packed full of improvements and new functionalities.

This update also introduces an additional option for SSO: a generic OAuth 2.0 provider is now available, expanding your authentication options and providing even more versatility. Another highlight of this update is improved notifications, so you can effortlessly stay in the know. Admins now have the ability to suspend and unsuspend users with ease. TOTP creation and editing can now be done directly from the browser.

If you’re a system operator, please note that using older PHP versions will now trigger a healthcheck warning. Support for PHP 7.4 and 8.0 will be discontinued soon. Admins are encouraged to upgrade to PHP 8.1 or higher and use the latest version of the passbolt API.  But it's not all about major features; this version also includes a number of other improvements and fixes to make your passbolt experience smoother and more efficient.

Upgrade to version 4.4 to take advantage of these improvements. Thank you for using and supporting passbolt!

## [4.4.0] - 2023-11-07
### Added
- PB-27950 As a user I can use generic OAuth2 as single sign on provider
- PB-27773 As an administrator I can deny access to the mobile setup screen with RBAC
- PB-27951 As system operator I should be warned in the healthcheck when using PHP < 8.1, as support for PHP versions 7.4 and 8.0 will soon be removed

### Improved
- PB-26123 Update message for LDAP sync adding users to groups
- PB-27948 Guest identification by their username should be case-insensitive, unless specified in configuration
- PB-27957 Send notifications to all administrators when an administrator is deleted
- PB-27941 Send notifications to administrators when an administrator loses its administrator role
- PB-28171 Enable the email digest by default

### Security
- PB-28274 Fixes an XSS Security issue with mail content sanitization

### Fixed
- PB-26158 As an administrator I should be able to use the memberof LDAP filter following with a ldap-query
- PB-27895 Fixes a wrong user passphrase policy URL in email the email sent after setting edition
- PB-25477 As an administrator, I should be able to recreate a user with an email that exists in the db via the command line
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8
- PB-27857 Fix help site release notes automation by adding flavour on help site release notes merge request

### Maintenance
- PB-27932 Improve code static by using cakedccakephp/phpstan
- PB-28079 Remove deprecation warnings from the test suite
