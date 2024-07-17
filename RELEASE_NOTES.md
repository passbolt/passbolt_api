Release song: TBD

Passbolt v4.9.0-test.2

## [4.9.0-test.2] - 2024-07-17
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8
