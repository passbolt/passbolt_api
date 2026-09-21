Release song: https://www.youtube.com/watch?v=gI6fQ2IXMjE

## [5.16.0] - 2026-09-16
### Added
- PB-52633 Add support for Offline Mode

### Fixed
- PB-54100 Fix account recovery notifications are sent to deleted admins
- PB-54504 Fix MissingTemplateException on .json endpoints when X-Requested-With: XMLHttpRequest is set
- PB-54574 Fix lock-wait-timeout on DELETE during group edit that removes a user

### Security
- PB-54489 Upgrade phpseclib/phpseclib to 3.0.57 (CVE-2026-84308, AIKIDO-2026-730961)
- PB-54532 Small upgrade for js-yaml

### Maintenance
- PB-49136 Update cakephp/migrations package to improve compatibility with PHP 8.5
- PB-53658 Use Request to DTO Mapping to automatic mapping of request data
- PB-53925 Add tests to assert session data do not trigger any code
- PB-54144 Update the Passbolt logo
- Renovate: Update adminer:standalone Docker digest
- Renovate: Update dependency cakephp/cakephp to v5.4.2
- Renovate: Update dependency composer/composer to v2.10.3
- Renovate: Update dependency league/flysystem to v3.35.3
