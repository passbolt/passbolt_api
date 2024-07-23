Release song: https://youtu.be/zUzd9KyIDrM?si=bPS9Qu1t351eZEHH

Passbolt v4.9.0 is a significant update that addresses long-standing user requests and enhances performance. In this release, a highly requested feature was introduced where the passwords workspace now displays the location of resources. This addition provides extra meta information to help users efficiently identify passwords and where they are located. Additionally, the search functionality has been improved to use resource locations as meta information. Users can now retrieve a resource by using the names of its parent folders, which can greatly simplify the process of finding passwords depending on your organisation's classification system.

The team has also focused on various performance improvements to meet the growing needs of organisations managing an increasing number of passwords. These enhancements also prepare the way for the upcoming v5.0.0, which will support more content types and include an additional encryption layer. Both the API and the browser extension have been optimised, resulting in a 50% improvement in retrieving and treating collections of resources, according to our benchmarks.

## [4.9.0] - 2024-07-23
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array
- PB-33664 Fix missing "is" in the database schema up to date sentence (GITHUB #517)

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package
