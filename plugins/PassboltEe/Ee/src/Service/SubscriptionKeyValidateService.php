<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */
namespace Passbolt\Ee\Service;

use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionValidationException;
use Passbolt\Ee\Form\SubscriptionKeyAsciiForm;
use Passbolt\Ee\Form\SubscriptionKeyDtoForm;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;

class SubscriptionKeyValidateService
{
    /**
     * The subscription form
     *
     * @var \Passbolt\Ee\Form\SubscriptionKeyAsciiForm
     */
    protected $subscriptionKeyAsciiForm;

    /**
     * The subscription form details
     *
     * @var \Passbolt\Ee\Form\SubscriptionKeyDtoForm
     */
    protected $subscriptionKeyDtoForm;

    /**
     * SubscriptionKey constructor.
     */
    public function __construct()
    {
        $this->subscriptionKeyAsciiForm = new SubscriptionKeyAsciiForm();
        $this->subscriptionKeyDtoForm = new SubscriptionKeyDtoForm();
    }

    /**
     * @param string $keyString key
     * @throw SubscriptionException if key is not found or invalid
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     */
    public function validate(string $keyString): SubscriptionKeyDto
    {
        try {
            $formatIsValid = $this->validateFormat($keyString);
            $keyDto = $this->getData($keyString);
            $dataIsValid = $this->validateDto($keyDto);
        } catch (\Exception $e) {
            throw new SubscriptionSignatureException($keyString, $this->getFirstErrorMessage());
        }

        if (!$formatIsValid) {
            throw new SubscriptionFormatException($this->getFirstErrorMessage());
        }

        if (!$dataIsValid) {
            throw new SubscriptionValidationException($this->getFirstErrorMessage(), $keyDto);
        }

        return $keyDto;
    }

    /**
     * Check if the subscription format is valid.
     *
     * @param string $subscriptionKey key
     * @return bool
     */
    public function validateFormat(string $subscriptionKey): bool
    {
        return $this->subscriptionKeyAsciiForm->execute(['key_ascii' => $subscriptionKey]);
    }

    /**
     * Check if the subscription data are valid (valid number of users, valid expiry date, etc..)
     *
     * @param \Passbolt\Ee\Model\Dto\SubscriptionKeyDto $keyDto key
     * @return bool true or false.
     * @throws \Exception $e if the data cannot be retrieved
     */
    public function validateDto(SubscriptionKeyDto $keyDto): bool
    {
        return $this->subscriptionKeyDtoForm->execute($keyDto->toArray());
    }

    /**
     * Extract the subscription info
     *
     * @param string $subscriptionKey key
     * @throws \Exception If the subscription format is not valid
     * @throws \Exception If the gpg public subscription key cannot be imported into the keyring
     * @throws \Exception If the subscription cannot be verified
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     */
    public function getData(string $subscriptionKey): SubscriptionKeyDto
    {
        return $this->subscriptionKeyAsciiForm->parse($subscriptionKey);
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        if (!empty($this->subscriptionKeyAsciiForm->getErrors())) {
            return $this->subscriptionKeyAsciiForm->getErrors();
        }

        if (!empty($this->subscriptionKeyDtoForm->getErrors())) {
            return $this->subscriptionKeyDtoForm->getErrors();
        }

        return [];
    }

    /**
     * Get main error message from the validation errors.
     *
     * @return string last error message.
     */
    public function getFirstErrorMessage(): string
    {
        $errors = $this->getErrors();
        if (!empty($errors)) {
            $firstColumnKey = array_keys($errors)[0];
            $secondColumnKey = array_keys($errors[$firstColumnKey])[0];

            return $errors[$firstColumnKey][$secondColumnKey];
        }

        return '';
    }
}
