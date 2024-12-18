<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.10.0
 */
namespace App\Service\OpenPGP;

use App\Utility\CommandRunner;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenerateOpenPGPKeyService
{
    /**
     * @var bool debug
     */
    private $debug = false;

    /**
     * @var string|null $gnupghome home directory
     */
    private $gnupghome = null;

    /**
     * Constructor - prevent use in production
     */
    public function __construct()
    {
        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            throw new ForbiddenException();
        }
        $this->gnupghome = env('GNUPGHOME', Configure::read('passbolt.gpg.keyring'));
        $this->assertKeyringAvailable();
    }

    /**
     * @param bool $verbose default false
     * @return array
     */
    public function generateMetadataKey(bool $verbose = false): array
    {
        $this->debug = $verbose;
        $keyname = $this->genMainKey();
        $fingerprint = $this->getFingerprintFromName($keyname);
        $this->addSubKey($fingerprint);

        $result = [
            'public_key' => $this->exportPublicKey($fingerprint),
            'private_key' => $this->exportPrivateKey($fingerprint),
            'fingerprint' => $fingerprint,
            'passphrase' => '',
        ];

        $this->deleteKey($fingerprint);

        return $result;
    }

    /**
     * @param array $cmd command to run
     * @return string
     */
    private function run(array $cmd): string
    {
        $process = CommandRunner::run($cmd);
        if (!$process || !$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        if ($this->debug) {
            echo $process->getOutput();
        }

        return $process->getOutput();
    }

    /**
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @return string key name
     */
    private function genMainKey(): string
    {
        $id = UuidFactory::uuid();
        $keyname = 'Passbolt Metadata Private Key - <no-reply+' . $id . '@passbolt.com>';
        $algo = 'ed25519';
        $usage = 'default';
        $expires = 'never';
        $this->run([
            'gpg','--batch',
            '--homedir', $this->gnupghome,
            '--pinentry-mode', 'loopback', '--passphrase', '',
            '--quick-generate-key', $keyname, $algo, $usage, $expires,
        ]);

        return $keyname;
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return void
     */
    private function deleteKey(string $fingerprint): void
    {
        $this->run([
            'gpg','--batch', '--yes',
            '--homedir', $this->gnupghome,
            '--delete-secret-key', $fingerprint,
        ]);
    }

    /**
     * Add cv25519 sub key for encryption
     *
     * @param string $fingerprint key fingerprint
     * @return void
     */
    private function addSubKey(string $fingerprint): void
    {
        $algo = 'cv25519';
        $usage = 'encrypt';
        $expires = 'never';
        $this->run([
            'gpg','--batch',
            '--homedir', $this->gnupghome,
            '--pinentry-mode', 'loopback', '--passphrase', '',
            '--quick-add-key', $fingerprint, $algo, $usage, $expires,
        ]);
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return string
     */
    private function exportPrivateKey(string $fingerprint): string
    {
        return $this->run([
            'gpg','--batch',
            '--homedir', $this->gnupghome,
            '--pinentry-mode', 'loopback', '--passphrase', '',
            '--armor', '--export-secret-keys', $fingerprint,
        ]);
    }

    /**
     * @param string $fingerprint key fingerprint
     * @return string
     */
    private function exportPublicKey(string $fingerprint): string
    {
        return $this->run([
            'gpg','--batch',
            '--homedir', $this->gnupghome,
            '--armor', '--export', $fingerprint,
        ]);
    }

    /**
     * @param string $name name used during generation
     * @return string fingerprint
     */
    private function getFingerprintFromName(string $name): string
    {
        $output = $this->run([
            'gpg',
            '--homedir', $this->gnupghome,
            '--list-keys', '--with-colons', $name,
        ]);

        return $this->extractFingerprint($output);
    }

    /**
     * @param string $output output of gpg command
     * @return string
     */
    private function extractFingerprint(string $output): string
    {
        $fingerprints = [];

        // Loop through each line
        $lines = explode("\n", $output, PHP_MAXPATHLEN);
        if ($lines === false) {
            throw new InternalErrorException('Empty output.');
        }
        foreach ($lines as $line) {
            // Each line is colon-separated; split it into fields
            $fields = explode(':', $line);
            if ($fields === false) {
                continue;
            }
            // If the line is a fingerprint line (indicated by 'fpr' in the first field)
            if (isset($fields[0]) && $fields[0] === 'fpr') {
                // The fingerprint is the 10th field (index 9)
                $fingerprints[] = $fields[9];
            }
        }

        $count = count($fingerprints);
        switch ($count) {
            case 1:
                return $fingerprints[0];
            case 0:
                throw new InternalErrorException('Fingerprint not found');
            default:
                throw new InternalErrorException('Too many fingerprints found.');
        }
    }

    /**
     * @return void
     */
    private function assertKeyringAvailable(): void
    {
        $directory = $this->gnupghome ?? false;
        if ($directory === false || !is_string($directory)) {
            throw new InternalErrorException(__('The keyring is not defined.'));
        }
        if (!is_dir($directory) || !is_readable($directory)) {
            $msg = __('The keyring directory is not accessible by the current user: {0}', $directory);
            throw new InternalErrorException($msg);
        }
    }
}
