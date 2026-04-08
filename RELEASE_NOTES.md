Release song: https://www.youtube.com/watch?v=GXbOROT0OuA

Passbolt 5.11.0-test.3 PRO.

## [5.11.0-test.3] - 2026-04-07
### Added
- PB-49875 OAuth support for smtp authentication
- PB-50158 Add a feature flag to enable/disable Safari availability on a Passbolt instance
- PB-50199 As an admin I can contain my_group_user in POST /groups.json
- PB-32992 As a user I can use PingOne as single sign on provider
- PB-50524 Move SCIM feature out of beta

### Fixed
- PB-49323 As a user creating a resource, I should not get a 500 if the secret passed is not an array of secrets
- PB-40266 Health-check issues on Ubuntu 24 when running while being in a directory without the +x permission bit for www-data user (GITHUB #571)
- PB-50021 As a guest, I should not get a 500 on GET /users.json?contain[pending_account_recovery_request]=1
- PB-49823 Fix misleading email notification footer
- PB-50028 GITHUB - Fix GPG authentication nonce UUID validation using incorrect comparison operand (#592, #596)
- PB-50121 Replace rand() with a static counter to generate unique bind-parameter placeholder (GITHUB #595)
- PB-50241 As a logged-in user I should not get a 500 when logging-in again
- PB-49902 As a user I cannot create a v4 resource with v5 resource type
- PB-49286 PBL-15-009 WP4: Non-transactional group member operations (Low)
- PB-49160 PBL-15-012 WP1: Potential admin lockout via malicious IdP request (Low)
- PB-49159 PBL-15-011 WP4: Lack of transaction wrapper in production sync (Low)
- PB-49285 PBL-15-008 WP4: ScimEntry uniqueness race condition (Medium)
- PB-49284 PBL-15-007 WP5: Potential DoS via pre-authentication GPG decryption (Low)
- PB-49151 PBL-15-003 WP3: Lack of bearer token expiry & revocation schemes (Medium)
- PB-49159 Fix enableSavePoints() not placed correctly

### Improved
- PB-50070 Align X-Frame-Options with CSP and add missing X-XSS-Protection header

### Maintenance
- PB-50133 Align allowCsvFormat variable name in plugin config.php
- PB-50173 Fix composer security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-32935)
- PB-49096 Remove unused MFA assets & pages served by the browser extension
