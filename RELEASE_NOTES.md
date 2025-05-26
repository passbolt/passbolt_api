Release song: https://www.youtube.com/watch?v=Nbav4oWMqEY

This is a maintenance release to fix reported issues from Pro customers where directory synchronization was returning various type errors. It also includes a security fix to only include user’s IP & browser agent information if enabled via configuration.

Thank you for the valuable feedback and patience!

## [5.1.1] - 2025-05-22
### Fixed
- PB-42594 Fix directory_sync ignore-create CLI command shared from help message is not working
- PB-42691 Make created & modified dates from AD/LDAP server optional
- PB-42592 Fix missing attribute in ldap default configuration file
- PB-42689 Fix type errors in directory_sync users & groups commands
- PB-42701 Fix the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured
