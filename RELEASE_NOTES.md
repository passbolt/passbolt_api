Release song: https://www.youtube.com/watch?v=6Ejga4kJUts

Version 4.4 of the Community Edition has launched with new capabilities and improvements.

With this release, users are able to manage TOTPs directly from the browser, providing an extended TOTP experience across all their devices. They can now be created, deleted, organised and shared with others just like any other resource type.

Another highlight of this release, administrators now have the ability to suspend/unsuspend users. This new feature will offer administrators with more control over access management of their instance. By example, they will be able to prevent access to the passbolt instance for users in temporary leave, therefore enforce company policies.

And that's not all – a number of fixes and enhancements have been implemented to improve user experience. Among them, notification emails are now aggregated in certain cases, including limiting emails when a user imports a large amount of passwords.

If you’re a system operator, please note that using older PHP versions will now trigger a healthcheck warning. Support for PHP 7.4 and 8.0 will be discontinued soon. Admins are encouraged to upgrade to PHP 8.1 or higher and use the latest version of the passbolt API.

Get the most out of passbolt – upgrade to version 4.4. Thanks for continuing to support passbolt and for being part of the community!


## [4.4.0] - 2023-11-07
### Added
- PB-27773 As an administrator I can deny access to the mobile setup screen with RBAC
- PB-27951 As system operator I should be warned in the healthcheck when using PHP < 8.1, as support for PHP versions 7.4 and 8.0 will soon be removed

### Improved
- PB-27948 Guest identification by their username should be case-insensitive, unless specified in configuration
- PB-27957 Send notifications to all administrators when an administrator is deleted
- PB-27941 Send notifications to administrators when an administrator loses its administrator role
- PB-28171 Enable the email digest by default

### Security
- PB-28274 Fixes an XSS Security issue with mail content sanitization

### Fixed
- PB-25477 As an administrator, I should be able to recreate a user with an email that exists in the db via the command line
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8
- PB-27857 Fix help site release notes automation by adding flavour on help site release notes merge request

### Maintenance
- PB-27932 Improve code static by using cakedccakephp/phpstan
- PB-28079 Remove deprecation warnings from the test suite
