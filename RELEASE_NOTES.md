Release song: https://www.youtube.com/watch?v=kymdKYtkJbQ

Passbolt is thrilled to announce that the v5.4.0 API Release Candidate is officially available for testing.

This version ships with encrypted metadata and the accompanying new resource types promoted to stable. These capabilities have been battle-tested for months, and the most remaining edge cases have been smoothed out so they can now be enabled for everyone. In addition, it also improves performance for large organisations that manage substantial numbers of users or resources and support for Czech language.

Head to GitHub and dive in! Make sure to follow the steps [here](https://community.passbolt.com/t/passbolt-beta-testing-how-to/7894). As always, your feedback is invaluable, give it a try and report any issues you come across.

Enjoy the testing journey! ❤️

## [5.4.0-rc.1] - 2025-08-11
### Added
- PB-43713 Translate the application in Czech
- PB-44285 Add endpoint to help clients enable E2EE by default for new instances
- PB-44184 As an administrator I should not be allowed to retrieve resources to migrate from v4 to v5 resource types from v4 resource types that are deleted
- PB-44071 Add a cleanup tasks to soft-delete inactive users with same usernames
- PB-44376 Set ECC key type as a default for new users
- PB-44405 Add new healthcheck to notify administrators when there are no active metadata key if E2EE is enabled
- PB-44406 Add new healthcheck to notify administrators when zero-knowledge disabled and the server does not have access to the shared metadata key
- PB-44407 Add new healthcheck to notify administrators when server cannot validate its own shared metadata private key
- PB-44416 Add metadata settings getting started endpoint
- PB-38155 Add JSON schema definition to resource types migrations
- PB-44474 Switch encrypted metadata plugin to stable
- PB-43631 As an admin running a command as root, I should see the name of the command in the suggestion proposed by the CLI

### Fixed
- PB-43187 Retrieve user last logged data from users table instead of the log to improve application performance
- PB-43922 Fix notification emails about a resource update
- PB-43709 Fix enabling E2EE without a key should trigger an error
- PB-44093 Fix a warning message in ActionLogsUsernameQueryStrategy
- PB-44177 Fix as a user I should not be allowed to create v4 resource if the resource type is deleted
- PB-44179 Fix as user I should not view/index v4 resource types if the resource type is deleted
- PB-43936 Fix IsValidEncryptedMetadataPrivateKey should log, then return false and not throw an exception if isMessageForRecipient fails
- PB-44182 Fix as user I should not be allowed to delete a v4 resource if v4 resource type is deleted
- PB-44181 Fix as user I should not be allowed to share a v4 resource if v4 resource type is deleted
- PB-44252 Fix as an admin I should not be able to set the role of a user to guest
- PB-44178 Fix as a user I should not be allowed to update v4 resource if the resource type is deleted
- PB-44180 Fix as user I should not view/index v5 resource types if the resource type is deleted
- PB-44186 Fix as an administrator I should not be able to rotate the metadata key for resources that have a deleted resource types
- PB-44189 Fix command line metadata commands should be loaded in debug mode only
- PB-43936 Fix isMessageForRecipient should work if encryption is done with main key
- PB-41818 Fix as a user setting a date as boolean the API should not return a 500 code response

### Maintenance
- PB-43524 Create a TestData plugin in plugins/PassboltCe
- PB-44087 Remove V331 backward compatibility migration
- PB-44267 Bump SeleniumApi plugin version
- PB-43752 Add assertJson assertions to folders endpoints
- PB-41818 Bump cakephp version to 5.2.6
