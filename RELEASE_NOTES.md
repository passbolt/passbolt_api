Release song: https://youtu.be/3RmQTYLD398?si=eKKvftgUpBTIm51p

TBD

## [4.11.0-test.1] - 2025-01-29
### Added
- PB-35761 As an administrator I receive an email if zero_knowledge_key_share is set to true and a new user completed the setup
- PB-36558 As an administrator I can mark metadata_keys as expired
- PB-35986 As an administrator I can share missing metadata private keys for users that needs them
- PB-35925 As an administrator I can see if users are missing access to metadata keys
- PB-37069 As an administration I can run a command to share metadata private keys with users that need them
- PB-37068 As a user I can see if I am missing metadata keys
- PB-36600 As an administrator I should be notified when an administrator expires a metadata key
- PB-35418 As an administrator I should receive an email notification when a metadata key is deleted
- PB-37361 As an administrator I can rotate metadata keys encrypting resources metadata
- PB-37697 As an administrator I can upgrade resources to v5 format
- PB-36576 Fix as a user I cannot create or edit a tag with an expired or deleted metadata key
- PB-35927 As an administrator I can define an allow_v4_v5_upgrade metadata type settings
- PB-35923 As an administrator I cannot add a new metadata key if there is only 2 that are active
- PB-34463 As an administrator I cannot reuse metadata keys as the account recovery key

### Fixed
- PB-36925 Cast configure usage to avoid fatal type error on missing fullBaseUrl
- PB-37097 Fix prevent to use v5 resource_type_ids if v5 flag is off
- PB-36930 Fix some email sentences not translated and markers errors in translation
- PB-37096 Fix healthcheck relying on symfony/process should fail gracefully in case of process run exception
- PB-36989 Fix namespace composer warnings
- PB-37343 Fixes postgres dump by adding PGPASSWORD env since .pgpass is not generated on the passbolt installation
- PB-37664 As an administrator running the healthCheck, the inactive users should not be calculated for the license check
- PB-38026 As an administrator running the cleanup command I should not see issues on soft deleted groups
- PB-38166 Passbolt app router should not fall back on Host header if full-base url is not set

### Security
- PB-37974 Upgrade CakePHP to v4.5.9

### Maintenance
- PB-35119 Fix tests failing when full base url is not-https
- PB-37000 Fix bug of wrong relation for Rbacs to Log.Actions.
- PB-37072 Fix LatestVersionApplicationHealthcheck test failing due to github not reachable
- PB-37071 Fix PHPUnit 10 deprecations
- PB-36237 Fix frequently failing TOTP setup/verify tests
- PB-38184 Fix synk vulnerability for nesbot/carbon PHP Remote File Inclusion
