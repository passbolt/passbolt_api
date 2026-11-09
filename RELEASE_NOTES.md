Release song: TBD

## [5.18.0-test.1] - 2026-11-09
### Added
- PB-54386 As an administrator I can see every healthcheck in the administration panel through the generic healthcheck API
- PB-54757 As an administrator I can exclude healthchecks by key or domain from the healthcheck API
- PB-52437 As an administrator I can enforce Duo subscriber verification from the MFA settings
- PB-55621 Adds a healthcheck warning administrators when Duo is enabled without subscriber verification
- PB-55530 As a user I can choose the Sakura and Layette themes
- PB-56051 As an administrator I can set a maximum number of users, groups and resources records in the database

### Fixed
- PB-54825 Renames the healthcheck domains to dash case and replaces the per-domain CLI options with --filter
- PB-55619 Enforces the Duo subscriber verification on Duo setup and login
- PB-55383 Retries group updates on database deadlock and returns a 409 when the lock persists
- PB-55475 Returns a 404 instead of a 500 on JSON requests to HTML-only pages
- PB-55613 Speeds up resources and folders index queries by joining on the user's accessible ids
- PB-55776 Fixes SCIM user provisioning failing with a 500 when externalId or userName contains non-ASCII characters
- PB-55783 Requires a JSON request on the current SSO settings endpoint

### Security
- PB-54749 Rejects private key material and multiple armored blocks when parsing public keys (AV-01)
- PB-54972 Fixes V5 resource share bypass with a blank metadata_key_type (SS-01)
- PB-55798 Prevents HTTP method override to stop bypass of method-based proxy rules (FM-02)
- PB-32905 Rejects secrets that are not encrypted for their recipient
- PB-55900 Fixes i18next-http-backend multiple CVEs (CVSS3.1 LOW)
- PB-55657 Upgrades composer/ca-bundle to 1.6.0 (AIKIDO-2026-256661)
- PB-55408 Upgrades spomky-labs/cbor-php to 3.3.5
- PB-55409 Upgrades web-auth/cose-lib to 4.7.2
- PB-56131 Upgrades symfony packages to fix composer audit vulnerability advisories
- PB-54930 Enables strict integrity validation in package-lock.json lint checks
- PB-54931 Enables package name validation in package-lock.json lint checks

### Maintenance
- PB-54542 Fixes tests not behaving well with the in-memory GPG backend changes
- PB-55968 Cleans up deprecated npm packages and package.json overrides
- PB-56100 Bumps the styleguide to v5.18.0
- PB-55255 Extends the insert dummy data command to populate LDAP settings in the organization settings
- PB-56150 Fixes the PHP version and environment variables in DDEV cron jobs
- Renovate: Update dependency cakephp/cakephp to v5.4.3
- Renovate: Update dependency cakephp/plugin-installer to v2.0.3
- Renovate: Update dependency firebase/php-jwt to v7.2.1
- Renovate: Update dependency ramsey/uuid to v4.9.4
- Renovate: Update dependency web-auth/webauthn-lib to v5.3.9
- Renovate: Update adminer:standalone Docker digest
