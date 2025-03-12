Release song: https://www.youtube.com/watch?v=pBZs_Py-1_0

Passbolt v4.12.0 introduces the final update in the version 4 series. This release completes the groundwork for version 5 and allows integrators to test the migration directly from the UI ahead of the stable release.

As always, this version also addresses community-reported issues, including fixes for UI inconsistencies and multi-selection shortcuts that were not working across all environments.

As a final update of the v4 series, system administrators are invited to upgrade their version of PHP to meet Passbolt v5’s minimum requirements: PHP 8.2. We posted a guide in our Weblog to help you with the process:
[Preparing for Passbolt v5: PHP 8.2 Requirement](https://www.passbolt.com/blog/preparing-for-passbolt-v5-php-8-2-requirement).

Thank you to the community for your feedback and patience — we’re almost there!


## [4.12.0] - 2025-03-12
### Added
- PB-39395 As an administrator I can contain permissions when upgrading folders to v5 format
- PB-39394 As an administrator I can contain permissions when upgrading resources to v5 format
- PB-38850 As an administrator I cannot rotate entities while two metadata keys are active
- PB-37699 As an administrator I can upgrade folders to v5 format
- PB-37363 As an administrator I can rotate metadata keys encrypting folders metadata
- PB-36582 As an administrator I cannot reuse a previously deleted metadata key

### Fixed
- PB-39512 Fix during metadata upgrade process, the resource_type_id field is now updated in the database
- PB-39399 Adds missing fields to metadata private keys in index response
- PB-39393 Fix limit value is null in pagination header response for rotate & upgrade endpoints
- PB-38770 Fix email subject for delete resource email when resource is v5
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format

### Maintenance
- PB-39629 Set next minimum PHP version to 8.2 as passbolt v5 will not support lower PHP versions
