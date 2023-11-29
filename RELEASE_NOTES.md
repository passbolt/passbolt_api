Release song: https://youtu.be/6JNwqRF32ZI

Passbolt version 4.4.2 has been released, primarily as a maintenance update to address specific issues reported by users. This version includes two main fixes.

The first fix concerns the Time-based One-Time Password (TOTP) feature. In the previous version, there was an issue where users could accidentally delete the TOTP secret for a resource while editing its description from the sidebar. This has been corrected in the latest update.

The second fix improves the performance of the application, specifically when users are retrieving their resources. This update is part of an ongoing effort to enhance the overall performance of the application, with further improvements planned for future releases.

We extend our gratitude to the community member who reported this issue.

## [4.4.2] - 2023-11-28
### Improved
- PB-27616 As a user I should see improved performances when retrieving resources on the GET resources.json entry point

### Fixed
- PB-28991 As a user emails should be resent if the first attempt failed
