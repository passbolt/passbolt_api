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

use Cake\Log\Log;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;

/**
 * Send mail using SMTP protocol
 */
class SmtpTransport extends \Cake\Mailer\Transport\SmtpTransport
{
    /**
     * @param array $config Config in file
     */
    public function __construct(array $config = [])
    {
        $config = $this->readConfigInDb($config);

        parent::__construct($config);
    }

    /**
     * @param array $configInFile config in File
     * @return array
     */
    protected function readConfigInDb(array $configInFile): array
    {
        $configInDb = null;

        try {
            $configInDb = (new SmtpSettingsGetSettingsInDbService())->getSettings();
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }

        return $configInDb ?? $configInFile;
    }
}
