Release song: https://www.youtube.com/watch?v=HR1KH4zElcY

Passbolt v4.5.0 named "Summer is Ending" brings a host of new features and improvements, all designed to make your password management experience more secure, efficient, and user-friendly.

At the heart of this release is the introduction of the Password Expiry feature, a much-anticipated functionality that allows administrators to enable the automatic expiry policy, enhancing security by ensuring that potentially passwords are rotated when someone loses access to resources, for example by leaving a group or the organization.

Alongside, we're excited to introduce the Russian translation, making Passbolt more accessible to a wider audience.

We've also expanded our SMTP settings to include Microsoft 365 and Outlook providers, responding to the community's feedback for more integration options. The section to help users install and configure the Windows desktop application feature is also now enabled by default.

Improvements in the performance of some important API endpoints and other security enhancements and bug fixes are also part of this release.

Thanks to everyone in our community for your ongoing support and contributions that made this release possible. Your feedback and involvement continue to shape Passbolt, enhancing our collective security and usability. Together, we're making password management better for everyone.

## [4.5.0] - 2024-02-08
### Added
- PB-23913 As a user I can see my passwords marked as expired when users lose permissions on these
- PB-23913 As an administrator I can activate the password expiry feature
- PB-28923 As a user I want to be able to use passbolt in Russian
- PB-21484 As an administrator I can define Microsoft 365 and Outlook providers in SMTP settings
- PB-19652 As an administrator I can cleanup groups with no members with the cleanup command
- PB-27707 As administrator, with RBAC I should be able to set “can see users workspace” to “Allow if group manager”
- PB-28716 Desktop application flag is now enabled by default
- PB-26203 Desktop app define the account kit exportation help page

### Improved
- PB-27616 Improve resources serialization performance on GET resources.json

### Security
- PB-29148 Bump selenium API plugin version to v4.5
- PB-29005 Upgrades phpseclib/phpseclib to fix composer audit security vulnerability
- PB-22336 As an admin I should be able to enable/disable request group managers to add users to groups emails separately (LDAP/AD)
- PB-28871 Mitigate supply chain attack on PR and lint lock files
- PB-28658 Mitigate supply chain attack on post npm install script

### Fixed
- PB-29200 Fixes the recover_user command (GITHUB #504)
- PB-29164 Fix recent InstallCommand changes breaking selenium tests
- PB-29132 Fix composer lock file not up-to-date message when installing dependencies
- PB-29160 Fix failing static analysis job in CI
- PB-29137 Fix failing in UsersEditDisableControllerTest file due to purifier
- PB-29113 Fix a typo in the email sent when admins lose their admin role
- PB-28130 Fix invalid cookie name should not trigger a 500
- PB-29007 Fix constantly failing test in RbacsUpdateControllerTest file
- PB-28991 Fix email queue entries not marked as sent

### Maintenance
- PB-28857 Require phpunit-speedtrap to track down slow tests
- PB-25516 Remove --dev from .gitlab test options, it has not effect and will break with composer v3
- PB-28844 Improves the methods testing email content
- PB-28845 Skip unauthenticated exception from logging
- PB-28653 Speed-up tests by mocking the client in healthcheck relevant tests
