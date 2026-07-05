<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V5140AddPasskeyCredentialsTable extends AbstractMigration
{
    /**
     * Create the table storing the users' passkey login credentials.
     *
     * Each row is one authenticator a user enrolled to sign in WITHOUT their passphrase. It holds
     * both:
     *  - the WebAuthn public credential (`data`, a serialized Webauthn\PublicKeyCredentialSource)
     *    used to verify the login assertion, plus `credential_id`/`sign_count`/`aaguid`/`transports`
     *    denormalised for lookup and display;
     *  - `server_kit_key`, the SERVER half of the passphrase kit (an extractable AES-256-GCM key
     *    exported as JWK, base64). This half is released to the client ONLY after a valid login
     *    assertion; combined with the client half (kept in the browser, never sent to the server) it
     *    decrypts the user passphrase locally. The server therefore never holds anything able to
     *    decrypt the passphrase on its own (zero-knowledge preserved).
     *
     * @return void
     */
    public function change(): void
    {
        $this
            ->table('passkey_credentials', [
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
            // credential on the login assertion.
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
            // Server half of the passphrase kit: extractable AES-256-GCM key as base64(JWK).
            // Released to the client only after a successful login assertion.
            ->addColumn('server_kit_key', 'text', [
                'default' => null,
                'null' => true,
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
            ->table('passkey_credentials')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->update();
    }
}
