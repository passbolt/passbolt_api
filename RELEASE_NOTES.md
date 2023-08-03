Release song: https://youtu.be/iIxxHmIPVN4

## API

### Fixed
- PB-25506 Fix LDAP sync not working after migrating to v4 by allowing administrators to select the field to map to username in LDAP connection
- PB-25536 Fix OpenLDAP group object class & group member attribute mismatch

### Security
- PB-25563 PBL-09-001 Mitigate usage of sensitive keywords in user and group custom filters
- PB-25564 PBL-09-002 Mitigate arbitrary LDAP data exfiltration via fields_mapping
