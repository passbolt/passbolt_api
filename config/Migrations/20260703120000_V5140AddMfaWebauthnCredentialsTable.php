<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V5140AddMfaWebauthnCredentialsTable extends AbstractMigration
{
    /**
     * Create the table storing the users' registered WebAuthn (FIDO2) credentials.
     *
     * Each row is one authenticator registered by a user as a multi-factor authentication device.
     * The authoritative credential material is kept in the `data` column (a serialized
     * Webauthn\PublicKeyCredentialSource); `credential_id`, `sign_count`, `aaguid` and `transports`
     * are denormalised for fast lookup and UI display.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $this
            ->table('mfa_webauthn_credentials', [
                'id' => false,
                'primary_key' => ['id'],
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('user_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            // Base64url encoded credential id. Used to build allowCredentials and to look up the
            // credential on assertion. Real-world authenticators keep this well under 512 chars.
            ->addColumn('credential_id', 'string', [
                'default' => null,
                'limit' => 512,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            // Serialized Webauthn\PublicKeyCredentialSource (authoritative credential material).
            ->addColumn('data', 'text', [
                'default' => null,
                'null' => false,
            ])
            // WebAuthn signature counter (uint32). Bumped on every successful assertion.
            ->addColumn('sign_count', 'biginteger', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            // JSON array of authenticator transports, denormalised for display (e.g. ["usb","nfc"]).
            ->addColumn('transports', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('aaguid', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            // User provided friendly name for the security key.
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('last_used', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(['user_id'])
            ->addIndex(['credential_id'], ['unique' => true])
            ->create();

        $this
            ->table('mfa_webauthn_credentials')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->update();
    }
}
