Release song: https://www.youtube.com/watch?v=GXbOROT0OuA

Passbolt 5.11.0 "Got To be Real" introduces improvements to enterprise authentication and integration capabilities, alongside continued security hardening.

This release adds support for OAuth-based SMTP authentication for Microsoft Exchange Online and expands SSO coverage with PingOne. It also includes the finalisation of SCIM following external audit fixes.

## SCIM: audit fixes and general availability (Passbolt Pro)
Following the external security audit conducted by Cure53, this release includes fixes addressing the identified findings in the SCIM provisioning implementation.

With these changes, SCIM is now considered stable and exits beta.

The audit-driven improvements strengthen validation, error handling, and overall robustness of the provisioning flow. SCIM is now ready for production use in environments requiring automated user lifecycle management.

## PingOne SSO support (Passbolt Pro)
Passbolt 5.11 adds support for PingOne as a new SSO provider, enabling organisations to authenticate users via their existing Ping Identity infrastructure.

The integration is based on **OpenID Connect (OIDC)** using the Authorization Code flow, with Passbolt delegating authentication to PingOne and receiving a verified user identity via ID tokens.

Administrators can configure PingOne from the SSO settings using the required environment ID, client ID, client secret, and base URL, with a dry-run option available to validate the setup before activation. Once enabled, users are redirected to PingOne for authentication and seamlessly logged into Passbolt, including during account recovery.

This addition expands Passbolt’s SSO coverage for enterprise environments and removes a key adoption blocker for organisations standardised on Ping Identity.

## SMTP OAuth support for Microsoft Exchange Online
Passbolt 5.11 introduces OAuth 2.0 support for SMTP with Microsoft Exchange Online, replacing legacy username/password authentication.

Administrators can configure the **OAuth (Client Credentials)** method by registering an application in Microsoft Entra ID and providing the required tenant ID, client ID, client secret, and service account email.

At runtime, Passbolt retrieves short-lived access tokens to authenticate SMTP connections without user interaction, improving security and aligning with modern authentication standards.

## Security improvements
This release continues the ongoing security hardening effort across the platform.

In addition to the SCIM audit fixes, improvements have been made to align with external audit recommendations and reduce potential attack surface in authentication and integration layers.

## Maintenance & performance
This release includes general performance improvements, particularly around background job processing and email delivery workflows.

Email-related operations are now more efficient and better distributed, reducing bottlenecks in high-load environments.

As usual, additional optimisations are already in progress for upcoming releases.

## Conclusion
As usual, the release is also packed with additional improvements and fixes. Check out the changelog to learn more.

Many thanks to everyone who provided feedback, reported bugs, and contributed to making passbolt better!

## [5.11.0] - 2026-04-09
### Added
- PB-49875 OAuth support for smtp authentication
- PB-50158 Add a feature flag to enable/disable Safari availability on a Passbolt instance
- PB-50199 As an admin I can contain my_group_user in POST /groups.json
- PB-50646 Add Permissions-Policy header on the API response

### Fixed
- PB-49323 As a user creating a resource, I should not get a 500 if the secret passed is not an array of secrets
- PB-40266 Health-check issues on Ubuntu 24 when running while being in a directory without the +x permission bit for www-data user (GITHUB #571)
- PB-50021 As a guest, I should not get a 500 on GET /users.json?contain[pending_account_recovery_request]=1
- PB-49823 Fix misleading email notification footer
- PB-50028 GITHUB - Fix GPG authentication nonce UUID validation using incorrect comparison operand (#592, #596)
- PB-50121 Replace rand() with a static counter to generate unique bind-parameter placeholder (GITHUB #595)
- PB-50241 As a logged-in user I should not get a 500 when logging-in again
- PB-49902 As a user I cannot create a v4 resource with v5 resource type

### Improved
- PB-50070 Align X-Frame-Options with CSP and add missing X-XSS-Protection header

### Maintenance
- PB-50133 Align allowCsvFormat variable name in plugin config.php
- PB-50173 Fix composer security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-32935)
- PB-49096 Remove unused MFA assets & pages served by the browser extension
