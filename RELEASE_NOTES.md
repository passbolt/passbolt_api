Release song: https://www.youtube.com/watch?v=pBZs_Py-1_0

Passbolt v4.12.0 introduces 2 new administration pages to manage the migration of metadata and the allowed content, laying groundwork for the upcoming v5 release and its new resource format. This beta feature eases admins, developers and integrators to explore and adapt their systems ahead of the transition.

This release also resolves an ergonomical issue where the multi-selection keyboard shortcuts are now adapted to the OS standard shortcuts.
A few fixes come with this release as well. Exporting resources now sets all the data properly and creation and edition of a resource sets all the metadata fields as expected, allowing integration to fully validate the metadata.

Thank you to the community for your feedback and support.

## [4.12.0-rc.1] - 2025-03-06
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
- PB-38770 Fix email subject for delete resource email when resource is v
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format

### Maintenance
- PB-39629 Set next minimum PHP version to 8.2 as passbolt v5 will not support lower PHP versions
