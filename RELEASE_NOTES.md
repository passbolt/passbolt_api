Release song: https://www.youtube.com/watch?v=d9WHUTKMD8k

The Passbolt API v5 is now available as a release candidate.
Passbolt new installation will see the password expiry enabled by default.
This version also introduces encrypted resource metadata (beta) for all users, along with numerous enhancements
and bug fixes. For full details, please see the changelog.

We would like to thank the community for their invaluable feedback.

## [5.1.0-rc.1] - 2025-05-12
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 Enable the metadata plugin by default

### Fixed
- PB-41736 Adjust the datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix the APP_BASE variable ignored when generated URLs with CakePHP

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json
- PB-42117 Populate metadata key ID for personal resources if null in payload

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar
