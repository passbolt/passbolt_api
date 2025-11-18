Release song: https://youtu.be/t12nOxmB278

Passbolt 5.7.2 fixes an issue introduced in v5.7.0 that affected the health check when it was run after the cleanup command.
The bug caused the server metadata private key to be incorrectly deleted, resulting in health check failures.
This has now been resolved, and the cleanup process works as expected.

We thank the community again for reporting this issue!

## [5.7.2] - 2025-11-17
### Fixed
- PB-46826 As an administrator running the cleanup task, the server metadata private key entry should not be deleted
