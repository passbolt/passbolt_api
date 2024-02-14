Release song: https://youtu.be/53YYph6Edd0

Passbolt is pleased to announce the immediate availability of version 4.5.2. This is a maintenance update that contains important fixes for both the API and browser extension, addressing issues reported by the community since version 4.5.0.

Most notably this update fixes a problem that previously prevented the autofill feature from working with certain web applications.

Additionally, the release improves the process for importing TOTPs from kdbx files on Windows, ensuring better support for TOTPs across various Keepass clients, including Keepass, KeepassXC, and Macpass.

Administrators would also be pleased to be able to host the API using PHP 8.3. While PHP 7.4 and PHP 8.0 are still supported on some distributions such as Debian, they will be discontinued soon and administrators are encouraged to upgrade to PHP 8.1 or higher and use the latest version of the passbolt API.

We would like to express our sincere thanks to the community members who brought issues to our attention and helped the team to make passbolt better.

## [4.5.2] - 2024-02-14
### Fixed
- PB-29621 As a user I should get a 400 if the locale passed in the URL is not a string
- PB-29526 As an administrator I should be notified of group removal when the operation is performed by a users directory synchronization
- PB-28867 As a user I should see an improved performance when requesting the folder index endpoint

### Improved
- PB-28635 As an administrator I can disable the email digest without having to change the command sending the emails

### Security
- PB-29680 Bump dependency composer/composer to v2.7.0

### Maintenance
- PB-29109 Support PHP 8.3 for passbolt API
- PB-29376 GITHUB-506 Bump dependency duosecurity/duo_universal_php to 1.0.2 (#506)
- PB-29514 Fix password expiry test which randomly fails
- PB-29625 Fix CI to support latest composer dependency version
