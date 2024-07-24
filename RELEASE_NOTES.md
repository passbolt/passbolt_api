Release song: https://youtu.be/zUzd9KyIDrM?si=bPS9Qu1t351eZEHH

Passbolt v4.9.0-test.5

## [4.9.0-test.5] - 2024-07-24
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint
- PB-33828 As an administrator I can define the deletion behaviour of users to suspended instead of deleted on directory synchronisation
- PB-33014 As an administrator I want to know if TLS/SSL verification is disabled when connecting to LDAP server
- PB-32284 As an administrator I can set the Default OpenID Connect param alg as optional as specified by the RFC

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array
- PB-33664 Fix missing "is" in the database schema up to date sentence (GITHUB #517)
- PB-33427 As a user logging-in with Azure SSO I should not get a 500 if the secret is expired
- PB-33881 Fixes a typo for the OAuth2 environment variable

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump
- PB-33688 Improves the performance of tags.json index endpoint
- PB-33650 Improves the performance of resources.json when retrieving tags

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package
