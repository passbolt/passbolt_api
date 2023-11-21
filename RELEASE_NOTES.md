Release song: https://youtu.be/RbmS3tQJ7Os?si=lp8QM5B-R65C8Jek

Passbolt v4.4.1 is a maintenance release aimed at addressing issues reported by the community, which were introduced in the previous release.

The update addresses an issue concerning user roles in email notifications. Previously, administrators received notifications when another administrator was deleted. However, the deletion of any user, regardless of their administrator status, was incorrectly reported as an administrator deletion. This issue has been resolved.

We extend our gratitude to the community members who reported these issues. Your support and patience are greatly appreciated.

## [4.4.1] - 2023-11-20
### Improved
- PB-28521 Alter the gpgkeys.uid column length to 769 to enable the synchronisation of user with very long names

### Fixed
- PB-27957 As an administrator I should be notified that an administrator was deleted only when an administrator has been deleted, and not a regular user

### Maintenance
- PB-27927 Remove unused user_agents table
- PB-28616 Refactor the email digest plugin code for easier usage and maintainability
