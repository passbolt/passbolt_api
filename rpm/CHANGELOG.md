# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

* [3.8.3-1] Thu, 1 Dec 2022 Daniel Del Rio <daniel@passbolt.com>
- PB-21631 Ensure the OpenPGP server key is in the keyring prior to sending any emails

* [3.8.1-1] Thu, 17 Nov 2022 Daniel Del Rio <daniel@passbolt.com>
- PB-21478 As an administrator, I should be able to edit SMTP settings having a sender email not being a valid email
- PB-21438 As an administrator using docker, I should be able to access the SMTP settings of my organization
- PB-21486 As an administrator, I can define the SMTP authentication method via the SMTP admin workspace
- PB-21481 As an administrator, I want emails to be sent with the sender settings defined in database, if defined in the database
- PB-21478 As an administrator, I should be able to edit SMTP settings having a sender email not being a valid email
- PB-21438 As an administrator using docker, I should be able to access the SMTP settings of my organization
- PB-21486 As an administrator, I can define the SMTP authentication method via the SMTP admin workspace
- PB-21481 As an administrator, I want emails to be sent with the sender settings defined in database, if defined in the database

* [3.8.0-2] Fri, 11 Nov 2022 Daniel Del Rio <daniel@passbolt.com>
- PB-21411 remove access_log configuration from location block    

* [3.8.0-1] Tue, 10 Nov 2022 Daniel Del Rio <daniel@passbolt.com>
- PB-19192: As an administrator, I want to manage SMTP settings in the administration workspace
- PB-19151: As a user, I want to use passbolt with the Solarized light theme
- PB-19151: As a user, I want to use passbolt with the Solarized dark theme
- PB-19200: GpgAuthenticator now asserts the message is a valid OpenPGP message prior to decryption on stage 0
- PB-19312: As a logged-in user, I want to see my first name and last name correctly displayed in email headers
- PB-18718: As a logged-in user, I want my locale not to be overwritten by the server config on pages served by the server
- PB-19261: As a logged-in user, I should not get an internal error if no filter is passed to the get resource.json entry point
- PB-19090: Protect forms from spell-jacking attack
- PB-19235: Migrate comments controllers logic into services
- PB-19603: Cover additional “add user to group” case: As group manager I can add a user to a group which have no resources shared with
- PB-6081: Move community plugins into plugins/PassboltCe
- PB-19621: Stop changing folders permissions in installation tests
- PB-19255 As an administrator I can trigger 500 errors on demand to test my logs

* [3.7.3-1] Tue, 27 Sep 2022 Diego Lendoiro <diego@passbolt.com>
- PB-19090 Protect forms from spell-jacking attack

* [3.7.2-1] Wed, 21 Sep 2022 Gerold Mougenel <gerold@passbolt.com>
- PB-18380 Let passbolt-configure script setup certbot for RHEL9 support
- PB-16983 Handles the lack of permissions on image directory when deleting
- PB-16898 Redesign download a supported browser to get started
- PB-18650 Add a check on mysql status in order to run mysql commands only when it's ready in unit tests
- PB-18664 Add retry logic to Gitlab CI jobs

* [3.7.1-1] Fri Aug 12 2022 Daniel Del Rio <gerold@passbolt.com>
- PB-18381 Fix source language typos
- PB-18397 Fix as an admin I can generate a server key with the webinstaller within an instance over http
- PB-17096 Fix resouce_types name and slug postgresql compatibility
- PB-18372 Bump styleguide version to 3.7.1

* [3.7.0-1] Thu July 28 2022 Gerold Mougenel <gerold@passbolt.com>
- PB-17098 Add rockylinux 9 support
- PB-16751 Add Redhat 9 support
- PB-16749 Add Ubuntu 22.04 support
- PB-16950 Add Spanish and Lithuanian support
- PB-14514 Add PHP8.0 support
- PB-14514 Fix PHP8.1 compatibility issues
- PB-16161 Create action log endpoint for user CRUD
- PB-16844 Common part of the user recovery and setup audit log

* [3.6.0-1] Wed May 25 2022 Gerold Mougenel <gerold@passbolt.com>
- PB-9739 OpenPGP key and message validation refactoring
- PB-14141 Enhanced public/private key validation rules
- PB-13685 Enhanced secret validation rules
- PB-14138 Refactor setup and recover related controllers with dependency injection
- PB-14510 Three trivial endpoints, such as GET on login are not logged anymore
- PB-16123 Remove key rotation logic from package post install scripts
- PB-15277 Remove mariadb dependency for RPM package
- PB-14947 Package versioning for RPM

* [3.5.0-3] Fri Apr 29 2022 Gerold Mougenel <gerold@passbolt.com>
- PB-15039 fixing repomd.xml signing issues

* [3.5.0-2] Wed Apr 13 2022 Gerold Mougenel <gerold@passbolt.com>
- PB-13923 Update the repository key to sign packages
- PB-13749 Update firewalld install check
- PB-13743 Fix bad condition logic so setup firewalld
- PB-13650 Delete jwt folder only while uninstalling
- PB-13783 Update selinux policy RPM package

* [3.5.0-1] Tue Jan 18 2022 Daniel Del Rio <daniel@passbolt.com>
- PB-13161 As LU I should be able to use passbolt with my Android mobile
- PB-13161 As LU I should be able to use passbolt with my IOS mobile
- PB-5967 As AD I can use passbolt with a PostgreSQL database provider [experimental]
- PB-5967 As AD I can migrate an existing instance to PostgreSQL with the help of the command line [experimental] and MySQL to Postgres migration tools, e.g. as described here: https://pgloader.readthedocs.io and here: https://pgloader.io/.
- PB-8513 As LU I can request gpg keys using pagination
- PB-13321 As a user I can use passbolt in Dutch
- PB-13321 As a user I can use passbolt in Japanese
- PB-13321 As a user I can use passbolt in Polish
- PB-12817 As LU I can import avatars having a jpeg extension
- PB-12943 As AD I should be able to see log when a user tries to sign-in with an invalid bearer token
- PB-12888 Improve performances of the operations requiring permissions accesses by replacing the single index on type by a combined index involving the requested columns
- PB-13177 As AD I should be able to see any gpg keys errors from the healthcheck
- PB-13183 As LU I should be able create resource having a name or a username of 255 characters long
- PB-13265 As AD I can create a JWT key pair even if the database is not set
- PB-13164 As AD I can cleanup duplicate entries in the favorites tables, groups_users and permissions
- PB-13217 PBL-06-011 Fix ACL on mobile transfer view controller
- PB-9887 Fix as AD I can send email digest from the /bin/cron script
- PB-12957 Fix multiple language issues reported by community
- PB-12914 Fix as a group manager I should not get multiple notifications when a group is updated
- PB-13158 As AD I should see a tip with proper directory permissions when the JWT assets healthcheck fails
- PB-12835 Move users setup/recover/register controllers logic into services to welcome the upcoming account recovery feature

* [3.4.0-3] Mon Dec 27 2021 Gerold Mougenel <gerold@passbolt.com>
- PB-12958 Fix RPM package, add https before fullbaseurl

* [3.4.0] Tue Dec 07 2021 Gerold Mougenel <gerold@passbolt.com>
- PB-9826 As a user I want to use passbolt natively on Edge
- PB-8371 As LU I want to see the login/MFA/recover/register screens in dark mode
- PB-8522 As LU I should see the MFA verify field having focus
- PB-9730 As AD I should be able to check avatars read issues from the healthcheck
- PB-8932 Fix as LU I should see an animation when I successfully configured MFA
- PB-9286 Fix as LU I should see the locale dropdown field of the setup/recover screen well positioned
- PB-9397 Fix as AD I shouldn't see an error on the healthcheck if the JWT auth is disabled and I never configured it
- PB-9114 Fix as lu I should be able to upload a transparent avatar in .png format.
- PB-9750 Fix spelling mistakes reported by the community
- PB-9762 Fix requesting /auth/login.json should not trigger an unexpected error
- PB-9888 Fix MFA & JWT refresh token issue, remove Bearer from the hashed session identifier
- PB-12817 Fix as LU I should be able to update jpeg avatar
- PB-7374 As soft deleted but logged in user I should be forbidden to request the API
- PB-9340 Fix email queue data should be stored and deserialized as json and not php
- PB-9311 Refactor JWT and MFA plugins for better code maintainability.
- PB-8320 Implement the tests that are marked as incomplete for cleaner continuous integration test reports
- PB-8211 Psalm set to level 4
- PB-9726 Fix do not load cleanup tasks unless in CLI mode
- PB-9753 Improve table fields validation tests, do not save entity when testing the validation of properties
- PB-9310 Move avatar file_storage logic into AvatarsTable
- PB-9785 Update JWT healthcheck help messages
- PB-9656 Migrate fields from utf8mb4 to a more performant encoding when possible
