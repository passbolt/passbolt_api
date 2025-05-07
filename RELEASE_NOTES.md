Release song: TBD

TBD

## [5.1.0-test.1] - 2025-05-07
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 The metadata plugin is enabled by default

### Fixed
- PB-41736 Adjust datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix APP_BASE not included in generated URLs

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
