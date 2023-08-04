Release song: https://youtu.be/iIxxHmIPVN4

Version 4.1.3 of passbolt is a maintenance & security release targeting mainly the LDAP & Active Directory connector.

On one hand, administrators using Active Directory will now be able to map passbolt username with their directory field of preference (userPersonalName by instance) via the administration section in the UI. While administrators using OpenLDAP will have access to fields mapping related to the Group Object Class via the UI as well.

On another hand, this release ships with security fixes (medium and low) identified during the July security audit by Cure53. This audit was targeting the users directory connector and as usual, the full report along with the mitigations will be fully disclosed on the website.

Thank you for helping us make Passbolt better!

## [4.1.3] - 2023-08-04

### Fixed
- PB-25506 Fix LDAP sync not working after migrating to v4 by allowing administrators to select the field to map to username in LDAP connection
- PB-25536 Fix OpenLDAP group object class & group member attribute mismatch

### Security
- PB-25563 PBL-09-001 Mitigate usage of sensitive keywords in user and group custom filters
- PB-25564 PBL-09-002 Mitigate arbitrary LDAP data exfiltration via fields_mapping
