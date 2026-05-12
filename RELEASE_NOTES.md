Release song: https://www.youtube.com/watch?v=0udIM6eooUA

Passbolt 5.12.0 makes the Safari browser extension generally available, ending the open beta period.
This release also introduces a new PIN code resource type, along with improvements to TOTP field detection and the usual round of security and dependency updates.

## Safari Extension Out of Beta
The Safari extension is now offered by default to all Safari users, on equal footing with Chrome, Firefox, and Edge.

This milestone reflects months of work across both our internal testing and the open beta period, during which organisations
enabled the extension on their own instances and gave feedback. Many thanks to everyone who joined the TestFlight program
for the open beta. Your feedback shaped this release.

## PIN code resource type
Passbolt 5.12 introduces a dedicated *Pin Code* resource type for securely storing standalone PINs such as door access codes,
safes, alarm systems, SIM codes, or device unlock codes.

Unlike workarounds based on passwords or custom fields, Pin Codes now have their own dedicated form, icon, validation, and generation flow.
PINs are strictly numeric and support 4 to 12 digits in accordance with the ISO 9564-1 standard.

Users can create, view, copy, and generate PIN codes directly from the browser extension, optionally alongside a secure note.
A dedicated PIN code column can also be displayed in the resource grid, while administrators can enable or disable the resource type from the administration settings.

Import and export are supported for both CSV and KDBX formats, with automatic detection of compatible PIN code entries during import.
This release also lays the groundwork for additional resource types in future releases.

## Maintenance and security
As usual, this release ships some third-party dependency upgrades and security advisory fixes, with no user-visible impact.

The release also refines the browser extension's detection of TOTP fields to reduce false positives in autofill. Many thanks to
the community members who reported cases where the extension picked up unintended fields. Clearly integrating with the wide variety
of forms across the web is a community effort, and your feedback is what makes it possible.

For administrators, the action logs purge command now covers additional entries, improving the audit logs performance.

## Conclusion
Many thanks to everyone who tried the Safari open beta, reported autofill issues, and contributed to making Passbolt better.

## What’s next
Passbolt is also preparing for offline mode support, allowing users to securely access encrypted resources even when temporarily disconnected from the server. More details will be shared in upcoming releases!

## [5.12.0] - 2026-05-12
### Added
- PB-51081 Adds pin code resource type
- PB-51516 Enables Safari by default

### Security
- PB-50625 Fixes GHSA-F886-M6HF-6M8V security vulnerability advisory (Medium)
- PB-50340 Upgrades picomatch package (Medium)
- PB-50538 Upgrades lodash package (Critical)
- PB-50895 Fixes bn.js security vulnerability advisory GHSA-378v-28hj-76wf (Medium)
- PB-50969 Fixes composer security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-40194)
- PB-51135 Fixes security vulnerability advisory affecting composer/composer package (CVE-2026-40261, CVE-2026-40176)
- PB-51151 Fixes i18next-http-backend security vulnerability advisory GHSA-r5fr-rjxr-66jc (Medium)
- PB-51152 Fixes uuid security vulnerability advisory GHSA-w5hq-g745-h8pq (Medium)
- PB-51448 Fixes security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-44167)
- PB-51208 Cleans up UserScimResource.php logged errors
- PB-51028 Sets SESSION_COOKIE_SAMESITE on Lax by default for all session engines

### Maintenance
- PB-50893 As an administrator I can purge action additional logs by action via the logs purge command
- PB-50914 Homogenizes CE and Pro codebase
- PB-51243 Fixes activity logging breaking after instance reset while executing Selenium tests
- PB-51428 Fixes dev test data inserting empty definitions for v5 resource types
- PB-51541 Fixes SCIM endpoints returning 500 errors on cloud when resourceType is not supported
