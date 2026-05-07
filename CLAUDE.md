# CLAUDE.md — Passbolt API

## Introduction
This is a Passbolt API back-end powering the passbolt clients written in PHP with CakePHP 5.x framework.

Key constraints:
- Feature plugins: each new features goes into it's dedicated plugins inside `./plugins` folder.
- CE plugins: each community edition features goes into `./plugins/PassboltCe` folder.
- Dev-specific plugins: developers related reusable plugins goes into `./plugins/PassboltDev` folder.
- Prefer minimal, low-risk diffs over large refactors.
- Databases: SQL queries should be cross-database supported. MySQL 5.7 & 8, MariaDB >= 10.4, and Postgres > 14.

## Golden commands (use these)
### Setup
- Start DDEV: `ddev start`
- Initiate dev environment (should be called only once on first setup): `ddev init_passbolt`

### Helpful commands
- Refresh application state (run composer, migration, and clear cache): `ddev refresh`

### Tests (run before proposing a final change)
- Unit: `ddev exec -d /var/www/html "vendor/bin/phpunit --filter <files-changed>"`
- Core domain tests: `ddev exec -d /var/www/html "vendor/bin/phpunit tests/TestCase"`
- CE domain tests: `ddev exec -d /var/www/html "vendor/bin/phpunit plugins/PassboltCe"`
- Full testsuite(all tests): `ddev composer test`

### Quality gates
- Auto-fix coding standard errors: `ddev composer cs-fix`
- Static analysis: `ddev analyze`

## Architecture map
Classic CakePHP architecture with feature plugin based approach.
- `config/` Configuration and database migration files
- `plugins/PassboltCe/` Community feature plugins
- `src/` Core logic like authentication, basic features (i.e. user management)
- `src/Command/` Core commands
- `src/Controller/` Core controllers
- `src/Service/` Core services
- `templates/` Server-rendered views and email templates
- `webroot/` Front controller

Request flow: Controller → Service Layer → Model → Response DTO

## Database & migrations
- Uses classic CakePHP model, entity, etc. convention style for database/model layer.
- Uses DTO layer to structure data transformation, stored at `src/Model/Dto` or `plugins/PassboltCe/<plugin>/src/Model/Dto` folder.
- Migration files are stored at `./config/Migrations` folder.
- Migration file format is `<Timestamp>_V<Current-Version>MigrationName.php`.

## Conventions
- Coding style: PSR-12 + CakePHP Code Style with custom rules (`phpcs.xml`)
- Prefer typed DTOs for controller/service layer boundaries.
- No new global helpers.
- New business logic goes in service layer, not in Controllers.
