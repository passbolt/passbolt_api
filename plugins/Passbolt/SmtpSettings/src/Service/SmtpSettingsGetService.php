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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Service;

use App\Error\Exception\FormValidationException;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Passbolt\WebInstaller\Form\EmailConfigurationForm;

class SmtpSettingsGetService
{
    public const SMTP_SETTINGS_SOURCE_FILE = 'file';
    public const SMTP_SETTINGS_SOURCE_DB = 'db';
    public const SMTP_SETTINGS_SOURCE_ENV = 'env';

    /**
     * @var string
     */
    private $passboltFileName;

    /**
     * @param string $passboltFileName The passbolt config file, modifiable for unit test purpose.
     */
    public function __construct(string $passboltFileName = CONFIG . DS . 'passbolt_.php')
    {
        $this->passboltFileName = $passboltFileName;
    }

    /**
     * Read STMP settings in the DB, or in file
     * Validates the setting and return them
     *
     * @return array
     * @throws \App\Error\Exception\FormValidationException if the data does not validate the EmailConfigurationForm
     */
    public function getSettings(): array
    {
        $form = new EmailConfigurationForm();
        $data = $this->readConfigInDbOrFile();

        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the smtp settings.'), $form);
        }

        return $form->getData();
    }

    /**
     * @return array
     */
    protected function readConfigInDbOrFile(): array
    {
        return $this->readConfigInDb() ?? $this->readConfigInFile();
    }

    /**
     * Reads the SMTP settings in DB
     *
     * @return array|null
     */
    protected function readConfigInDb(): ?array
    {
        $config = (new SmtpSettingsGetSettingsInDbService())->getSettings();
        if (!is_null($config)) {
            $config['source'] = self::SMTP_SETTINGS_SOURCE_DB;
        }

        return $config;
    }

    /**
     * @return array
     */
    protected function readConfigInFile(): array
    {
        $config = TransportFactory::get('default')->getConfig();
        if ($this->isSmtpDefinedInPassboltConfigFile()) {
            $config['source'] = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE;
        } else {
            $config['source'] = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_ENV;
        }
        $from = Mailer::getConfig('default')['from'];
        foreach ($from as $email => $name) {
            $config['sender_email'] = $email;
            $config['sender_name'] = $name;
        }

        return $config;
    }

    /**
     * Checks that the passbolt file is readable.
     *
     * Loads the config and checks if both SMTP config and sender email
     * are defined.
     *
     * This method is mocked in tests, its functionality is not unit tested: handle with care.
     *
     * @return bool true if SMTP and sender info are defined in config/passbolt.php
     */
    protected function isSmtpDefinedInPassboltConfigFile(): bool
    {
        if (!$this->isPassboltConfigFileFoundAndReadable()) {
            return false;
        }

        $fileConfig = require $this->passboltFileName;

        $isEmailTransportDefinedInConfig = array_key_exists('EmailTransport', $fileConfig);
        $isEmailSenderDefinedInConfig = array_key_exists('Email', $fileConfig);

        return $isEmailSenderDefinedInConfig && $isEmailTransportDefinedInConfig;
    }

    /**
     * @return bool
     */
    protected function isPassboltConfigFileFoundAndReadable(): bool
    {
        return \file_exists($this->passboltFileName) && \is_readable($this->passboltFileName);
    }
}
