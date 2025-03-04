Release song: TBD

## [4.12.0-test.1] - 2025-03-05
### Added
- PB-39395 As an administrator I can contain permissions when upgrading folders to v5 format
- PB-39394 As an administrator I can contain permissions when upgrading resources to v5 format
- PB-38850 As an administrator I cannot rotate entities while two metadata keys are active
- PB-37699 As an administrator I can upgrade folders to v5 format
- PB-37363 As an administrator I can rotate metadata keys encrypting folders metadata
- PB-36582 As an administrator I cannot reuse a previously deleted metadata key

### Fix
- PB-39512 Fix during metadata upgrade process, the resource_type_id field is now updated in the database
- PB-39399 Adds missing fields to metadata private keys in index response
- PB-39393 Fix limit value is null in pagination header response for rotate & upgrade endpoints
- PB-38770 Fix email subject for delete resource email when resource is v
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format
