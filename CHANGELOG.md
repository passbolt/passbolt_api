## [Unreleased]
### Added
- PASSBOLT-2694: Implement the Web Installer feature

### Improved
- PASSBOLT-3119: The Web Installer should control the route with a middleware
- PASSBOLT-3153: The Web Installer healtchecks should ensure the config files can be written before continuing
- PASSBOLT-3120: Improve the Web Installer code coverage
- PASSBOLT-3127: The Web Installer should change the config folder permissions after the installation is completed
- PASSBOLT-3166: Add PHP 7.3 job on travis

### Fixed
- PASSBOLT-3150: I should not see duplicates rows when I filter my passwords by keywords
- PASSBOLT-2963: Persist the license in a file
- GITHUB-290: A user who have not completed the setup should be allowed to request a new token using recover
