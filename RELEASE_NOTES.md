Release song: TBD

## [5.17.0-test.1] - 2026-10-08
### Added
- PB-53318 Add WebAuthn / Passkey as a new MFA provider for the browser extension
- PB-54953 Add the mobile-delegated WebAuthn ceremony flow enabling passkey MFA from the Passbolt mobile app
- PB-39607 Add a cleanup process to soft-delete active resources that no longer have an associated secret
- PB-54494 Add configuration keys to govern SCIM admin token expiry and the near-expiry window
- PB-54495 Add a ScimTokenExpiryChecker service to compute SCIM admin token expiry state
- PB-54496 Reject SCIM requests presenting an expired admin token at authentication time
- PB-54497 Add a ScimUnauthenticatedResponseMiddleware returning a structured 401 payload on SCIM auth failure
- PB-54498 Add a healthcheck warning administrators when a SCIM admin token is nearing expiry
- PB-54499 Send admin notification emails when a SCIM admin token is near expiry or has expired
- PB-55263 Add an "Is encrypted for its recipient" datacheck in secrets

### Fixed
- PB-53564 Exclude authentication tokens from the SQL dump command
- PB-53970 Flush all users' MFA settings when an administrator disables MFA for the organization
- PB-54696 Shorten the TTL of mfa_setup and mfa_verify authentication tokens
- PB-54701 Enforce one active MFA ceremony handle per user
- PB-54828 Fix a null type value in the ResourceTypes slug definition
- PB-54830 Add an index on the users.username column to speed up user lookups
- PB-54831 Add a composite index on scim_entries(scim_name, foreign_model) to speed up SCIM lookups
- PB-54832 Add a retry loop to UserScimResource::create() to withstand concurrent SCIM writes
- PB-54833 Map 500 responses from the SCIM endpoint to 429 with a Retry-After header on concurrency conflicts
- PB-54856 Reduce the number of queries inside the loop in FoldersRelationsSortService
- PB-54857 Add a limit to isItemPersonal() when checking if an item is personal
- PB-54858 Improve setSecretRevisionInData() to not search the full collection each time
- PB-54980 Fix the wrong CTA URL in offline mode emails
- PB-54990 Deactivate all authentication tokens on user soft-delete
- PB-55138 Reduce PASSBOLT_AUTH_JWT_REFRESH_TOKEN expiry to 1 hour

### Security
- PB-54638 Fix blind SSRF via avatar upload (AL-02)
- PB-54640 Fix MFA brute-force limiter not counting failed TOTP codes submitted to the JSON endpoint (AL-01)
- PB-54703 Fix SSRF egress guard bypass in SSO provider URL validation (AS-01)
- PB-55268 Fix braces security vulnerability GHSA-vfj7-8cjw-p6xm (CVSS 3.1 HIGH)

### Maintenance
- PB-32905 Add a test case asserting SecretNotEncryptedForRecipient behaviour on share
- PB-36615 Fix flaky InstallCommand tests by hardcoding test data values
- Renovate: Update dependency league/oauth2-client to v2.9.1
- Renovate: Update dependency firebase/php-jwt to v7.1.1
- Renovate: Update dependency psalm/phar to v6.17.1
- Renovate: Update dependency ergebnis/phpunit-slow-test-detector to v2.25.0
- Renovate: Update dependency cakephp/cakephp-codesniffer to v5.3.2
- Renovate: Update dependency league/flysystem to v3.36.0
- Renovate: Update adminer:standalone Docker digest
