<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.8.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Passbolt\SmtpSettings\Mailer\Transport;

use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;

/**
 * Send mail using SMTP protocol
 */
class SmtpTransport extends \Cake\Mailer\Transport\SmtpTransport
{
    /**
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        $config = $this->readConfigInDb($config);

        parent::__construct($config);
    }

    /**
     * @param array $fallbackConfig config in File
     * @return string[]
     */
    protected function readConfigInDb(array $fallbackConfig): array
    {
        $configInDb = (new SmtpSettingsGetSettingsInDbService())->getSettings();

        if (isset($configInDb)) {
            return [
                'className' => self::class,
                'sender_name' => $configInDb['sender_name'],
                'sender_email' => $configInDb['sender_email'],
                'host' => $configInDb['host'],
                'port' => $configInDb['port'],
                'tls' => $configInDb['tls'] ?? null,
                'username' => $configInDb['username'],
                'password' => $configInDb['password'],
            ];
        }

        return $fallbackConfig;
    }
}
