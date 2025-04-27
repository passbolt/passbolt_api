Release song: TBD

TBD

## [5.1.0-test.1] - 2025-04-28
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-37364 As an administrator I can get and rotate tags with an expired metadata key
- PB-37702 As an administrator I can upgrade tags from v4 format to v5
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-40275 Add support for email claim alias for OAUTH2 provider via server config
- PB-41628 Enforces the activation of the metadata plugin

### Fixed
- PB-41820 INC-262 - New pro subscription keys are failing and triggering 500s
- PB-41736 Adjust datacheck command to support v5 resources
- PB-40274 Fix azure SSO asking for password everytime even after setting prompt to false
- PB-41769 Fix action_logs_purge command only purging 100 records

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar
