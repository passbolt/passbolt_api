Release song: https://www.youtube.com/watch?v=Hef-Zf6frk0

## [5.17.0] - 2026-10-20
### Added
- PB-53318 As a user I can add passkeys as multifactor authentication provider
- PB-54953 As a user I can use passkeys as multifactor authenticator from the Passbolt mobile app
- PB-39607 As an administrator I can cleanup soft-delete active resources that no longer have an associated secret
- PB-54494 As an administrator I can configure SCIM admin token expiry and the near-expiry window
- PB-54498 Adds a healthcheck warning administrators when a SCIM admin token is nearing expiry
- PB-54499 As an administrator I get notified by email when a SCIM admin token is near expiry or has expired
- PB-55263 Adds an "Is encrypted for its recipient" data check in secrets

### Fixed
- PB-54495 Adds a ScimTokenExpiryChecker service to compute SCIM admin token expiry state
- PB-54496 Rejects SCIM requests presenting an expired admin token at authentication time
- PB-54497 Adds a ScimUnauthenticatedResponseMiddleware returning a structured 401 payload on SCIM auth failure
- PB-53970 Flushes all users' MFA settings when an administrator disables MFA for the organization
- PB-54696 Shortens the TTL of mfa_setup and mfa_verify authentication tokens
- PB-54701 Enforces one active MFA ceremony handle per user
- PB-54828 Fixes a null type value in the ResourceTypes slug definition
- PB-54830 Adds an index on the users.username column to speed up user lookups
- PB-54831 Adds a composite index on scim_entries(scim_name, foreign_model) to speed up SCIM lookups
- PB-54832 Adds a retry loop to UserScimResource::create() to withstand concurrent SCIM writes
- PB-54833 Maps 500 responses from the SCIM endpoint to 429 with a Retry-After header on concurrency conflicts
- PB-54856 Reduces the number of queries inside the loop in FoldersRelationsSortService
- PB-54857 Adds a limit to isItemPersonal() when checking if an item is personal
- PB-54858 Improves setSecretRevisionInData() to not search the full collection each time
- PB-54980 Fixes the wrong CTA URL in offline mode emails
- PB-54990 Deactivates all authentication tokens on user soft-delete
- PB-55138 Reduces PASSBOLT_AUTH_JWT_REFRESH_TOKEN expiry to 1 hour

### Security
- PB-53564 Excludes authentication tokens from the SQL dump command
- PB-54638 Fixes blind SSRF via avatar upload (AL-02)
- PB-54640 Fixes MFA brute-force limiter not counting failed TOTP codes submitted to the JSON endpoint (AL-01)
- PB-54703 Fixes SSRF egress guard bypass in SSO provider URL validation (AS-01)
- PB-55268 Fixes braces security vulnerability GHSA-vfj7-8cjw-p6xm (CVSS 3.1 HIGH)

### Maintenance
- Renovate: Update dependency league/oauth2-client to v2.9.1
- Renovate: Update dependency firebase/php-jwt to v7.1.1
- Renovate: Update dependency psalm/phar to v6.17.1
- Renovate: Update dependency ergebnis/phpunit-slow-test-detector to v2.25.0
- Renovate: Update dependency cakephp/cakephp-codesniffer to v5.3.2
- Renovate: Update dependency league/flysystem to v3.36.0
- Renovate: Update adminer:standalone Docker digest
