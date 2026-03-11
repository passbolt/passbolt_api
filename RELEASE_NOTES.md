Release song: https://www.youtube.com/watch?v=9Nv-WHmjN7s

Passbolt 5.10 "You've Got the Love" introduces the first Safari-compatible version of the Passbolt browser extension. The extension is currently available as a beta preview for testers who want to try it and provide feedback ahead of the stable release. This version also brings new productivity features such as TOTP autofill and tags visible in the grid, along with security hardening and performance improvements.

## Safari beta support (TestFlight preview)

Passbolt 5.10 introduces the first Safari-compatible version of the Passbolt browser extension. The Safari extension is currently available as a build distributed through TestFlight via this [public link](https://testflight.apple.com/join/Q758nSft) for users who want to try it and provide feedback ahead of the stable release. The extension is currently distributed this way while work continues toward a stable Safari release.

Learn how to get started with the Safari beta in the [dedicated guide](https://community.passbolt.com/t/passbolt-safari-extension-now-available-in-open-beta/14182).

## TOTP autofill

Users can now autofill one-time passwords (TOTP) directly in login forms, similar to how usernames and passwords are autofilled.

When a webpage contains a TOTP field, Passbolt detects it and proposes relevant resources that contain a configured TOTP secret.
Users can then select the resource to fill the current one-time password directly into the form.

TOTP autofill can be triggered either from the in-form menu or from the Quick Access interface, allowing users to complete multi-factor authentication without manually copying codes between applications.

## Tags visible in the grid (Passbolt Pro)

Tags are now displayed directly in the resources grid, making it easier to identify and filter resources without opening the resource details view.

A new tags column shows the tags associated with each resource. Tags are displayed in alphabetical order and remain clickable, allowing users to filter the workspace by selecting a tag directly from the grid.

When multiple tags exist, the grid displays as many as possible within the column width and indicates additional tags using a counter with a tooltip showing the remaining tags.

This update also modernises the tag codebase and lays the groundwork for further improvements to tagging capabilities.

## Security improvements

Passbolt team is currently preparing its First Level Security Certification (CSPN) with the French National Cybersecurity Agency (ANSSI). This release includes some fixes following the CSPN pre-audit evaluation done in partnership with Quarkslab and an external audit of SCIM provisioning by Cure53. This release addresses the findings identified during both audits.

One notable issue is around [CSV injection](https://owasp.org/www-community/attacks/CSV_Injection), e.g. when CSV exports could be susceptible to [formula injection](https://owasp.org/www-community/attacks/CSV_Injection) when opened in spreadsheet software. This issue was known and classified as out of scope, as exported CSV files are not intended to be opened in spreadsheets but with the password manager they were generated for. However we revisited this decision and settled for a security-by-default approach: CSV export is now disabled by default, fixing the bigger problem of credentials being potentially exported in plaintext. Organisations that still rely on it can re-enable the feature through configuration. Encrypted KDBX export remains available and is the recommended format for credential portability. Looking ahead, we plan to support the FIDO CFX format in a future release to further standardise credential import and export across tools.

Content Security Policy enforcement has been extended to close remaining gaps, further reducing the attack surface in case of a breach. Because the browser extension serves its own code locally rather than relying on the API, sensitive operations were already well protected by design against server-side injection.

Additionally an external security audit of SCIM provisioning has been completed, and this release includes fixes for a number of the findings. We are actively working through the remaining issues and will publish the full audit results once that work is done. SCIM will exit beta and ship on Passbolt Cloud as soon as all findings are resolved.

## Maintenance & performance

This release brings a major upgrade to React 18, resulting in up to 20% faster rendering and the elimination of rare visual glitches that could cause flashes during navigation.

First load times have also improved substantially. Large organisations with thousands of resources will notice the biggest difference, with initial data processing now up to 20% faster.

Bear with us, more optimisations are already in the pipeline for future releases.

## Conclusion

As usual, the release is also packed with additional improvements and fixes. Check out the changelog to learn more.

Many thanks to everyone who provided feedback, reported bugs, and contributed to making passbolt better!

## Changelog
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
- [PRO] PB-47973 As an administrator I can synchronize with active directory longer entries in order to support 2 or more bytes alphabets
- [PRO] PB-49152 PBL-15-004 WP1: Fixes unsalted SHA256 hashing of bearer tokens in SCIM
- [PRO] PB-49148 PBL-15-002 WP3: Fixes suboptimal token generation randomness of SCIM bearer token
- [PRO] PB-49153 PBL-15-005 WP2: Fixes race condition in SCIM user creation endpoint
- [PRO] PB-49158 PBL-15-010 WP4: Fixes directory entry foreign key race condition

### Security
- [PRO] PB-49154 PBL-15-006 WP2: Disable user enumeration via error messages on SCIM user creation endpoint

### Maintenance
- PB-48556 Fixes CVE-2026-25129 security vulnerability advisory for psy/psysh package
- PB-47677 Upgrades firebase/php-jwt to version v7.0.0
- PB-47628 Upgrades cakephp/cakephp to v5.2.12
- PB-48555 Fix CVE-2026-24765 security vulnerability advisory for phpunit/phpunit package
- PB-48396 Update composer/composer package to 2.9.5 to fix CVE CVE-2026-24739 in symfony/process package
