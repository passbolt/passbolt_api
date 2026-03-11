Release song: TBD

## [5.10.0] - 2026-03-11
### Added
- PB-48415 As an administrator, I can define the export policies to prevent CSV Export RCE
- PB-45576 As a logged-in user, the user ID only should be stored in session
- PB-24273 GET /auth/logout endpoint is now disabled by default
- PB-48148 Enforces content security policy

### Fixed
- PB-48092 Fixes incorrect client IP in error logs by moving HttpProxyMiddleware upper in the middlewares chain
- PB-48208 POST /mfa/verify/yubikey should not trigger 500
- PB-43183 Improve folders cascade delete performance by refactoring code using iterative BFS and batch operations
- PB-49323 As a user creating a resource, I should not get a 500 if the secret passed is not an array of secrets
- PB-47973 As an administrator I can synchronize with active directory longer entries in order to support 2 or more bytes alphabets
- PB-49152 PBL-15-004 WP1: Fixes unsalted SHA256 hashing of bearer tokens in SCIM
- PB-49148 PBL-15-002 WP3: Fixes suboptimal token generation randomness of SCIM bearer token
- PB-49153 PBL-15-005 WP2: Fixes race condition in SCIM user creation endpoint
- PB-49158 PBL-15-010 WP4: Fixes directory entry foreign key race condition

### Security
- PB-49154 PBL-15-006 WP2: Disable user enumeration via error messages on SCIM user creation endpoint

### Maintenance
- PB-48556 Fixes CVE-2026-25129 security vulnerability advisory for psy/psysh package
- PB-47677 Upgrades firebase/php-jwt to version v7.0.0
- PB-47628 Upgrades cakephp/cakephp to v5.2.12
- PB-48555 Fix CVE-2026-24765 security vulnerability advisory for phpunit/phpunit package
- PB-48396 Update composer/composer package to 2.9.5 to fix CVE CVE-2026-24739 in symfony/process package
