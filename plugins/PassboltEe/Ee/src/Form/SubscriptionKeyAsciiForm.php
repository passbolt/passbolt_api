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
 * @since         3.1.0
 */
namespace Passbolt\Ee\Form;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Log\Log;
use Cake\Validation\Validator;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Model\Dto\SubscriptionKeyDto;

/**
 * Class SubscriptionKeyAsciiForm
 *
 * @package Passbolt\Ee\Form
 *
 * This form is used to validate the raw subscription key data, e.g. the key in the
 * ASCII form, e.g. as a signature + json object. It doesn't look at the content of the json
 * object itself, this is handled by SubscriptionKeyJsonDataForm
 *
 * This form is only used to validate the data, it does not actually save the data in the database
 * on execute().
 */
class SubscriptionKeyAsciiForm extends Form
{
    /**
     * Gpg object.
     *
     * @var \App\Utility\OpenPGP\Backends\Gnupg
     */
    protected $_gpg;

    /**
     * SubscriptionKeyAsciiForm constructor.
     *
     * @param \Cake\Event\EventManager|null $eventManager event manager
     */
    public function __construct(?EventManager $eventManager = null)
    {
        $this->_gpg = OpenPGPBackendFactory::get();

        parent::__construct($eventManager);
    }

    /**
     * Subscription key schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('key_ascii', 'text');
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('key_ascii', 'create', __('A subscription key is required.'))
            ->notEmptyString('key_ascii', __('The subscription key should not be empty.'))
            ->add('key_ascii', 'is_valid_subscription_format', [
                'last' => true,
                'rule' => [$this, 'checkSubscriptionFormat'],
                'message' => 'The subscription format is not valid.',
            ])
            ->add('key_ascii', 'is_valid_subscription', [
                'last' => true,
                'rule' => [$this, 'checkSignature'],
                'message' => SubscriptionSignatureException::MESSAGE,
            ]);

        return $validator;
    }

    /**
     * Check if a subscription is in a valid format.
     *
     * @param string $value The subscription
     * @param array $context not in use
     * @return bool
     */
    public function checkSubscriptionFormat(string $value, ?array $context = null)
    {
        try {
            $this->getArmoredSignedSubscription($value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Check if the subscription is signed properly.
     *
     * @param string $value The subscription
     * @param array|null $context not in use
     * @return string|bool
     */
    public function checkSignature(string $value, ?array $context = null)
    {
        try {
            $this->parse($value);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Parse the subscription.
     *
     * @param string|null $keyAscii key in ascii.
     * @return \Passbolt\Ee\Model\Dto\SubscriptionKeyDto
     * @throws \Exception If the subscription format is not valid
     */
    public function parse(?string $keyAscii = null): SubscriptionKeyDto
    {
        if (empty($keyAscii) && !empty($this->getData('key_ascii'))) {
            $keyAscii = $this->getData('key_ascii');
        }

        $armoredSignedSubscription = $this->getArmoredSignedSubscription($keyAscii);

        /** @var string $subscriptionInfoStr */
        $subscriptionInfoStr = $this->_verifySignature($armoredSignedSubscription);
        $subscriptionInfo = \json_decode($subscriptionInfoStr, true);
        if (is_null($subscriptionInfo)) {
            throw new \Exception(__('The subscription cannot be verified. Parse error.'));
        }

        $subscriptionInfo['data'] = trim($keyAscii);

        return SubscriptionKeyDto::createFromArray($subscriptionInfo);
    }

    /**
     * Get the armored subscription.
     *
     * @param string $keyAscii key in ascii
     * @return string The armored signed subscription
     * @throws \Exception If the subscription format is not valid
     */
    public function getArmoredSignedSubscription(string $keyAscii)
    {
        $armoredSignedSubscription = base64_decode($keyAscii);
        if (!$armoredSignedSubscription) {
            throw new \Exception(__('The subscription format is not valid.'));
        }

        $isSignedMessage = $this->_gpg->isParsableArmoredSignedMessage($armoredSignedSubscription);
        if (!$isSignedMessage) {
            throw new \Exception(__('The subscription format is not valid. Invalid format.'));
        }

        return $armoredSignedSubscription;
    }

    /**
     * Verify the subscription signature
     *
     * @param string $subscriptionSigned The signed subscription to verify.
     * @psalm-suppress InvalidNullableReturnType always returns a string
     * @return string The subscription info.
     * @throws \Exception If the gpg public subscription key cannot be imported into the keyring
     * @throws \Exception If the subscription cannot be verified
     */
    protected function _verifySignature(string $subscriptionSigned): string
    {
        $msg = __('The subscription key cannot be verified.');
        $subscription = '';
        $filePublicKey = Configure::read('passbolt.plugins.ee.subscriptionKey.public');
        if (!$filePublicKey || !file_exists($filePublicKey)) {
            $msg .= ' ' . __('The passbolt OpenPGP public key could not be found.');
            throw new SubscriptionSignatureException($subscriptionSigned, $msg);
        }
        $subscriptionPublicKey = file_get_contents($filePublicKey);
        $fingerprint = $this->_gpg->importKeyIntoKeyring($subscriptionPublicKey);
        $this->_gpg->setVerifyKeyFromFingerprint($fingerprint);
        try {
            $this->_gpg->verify($subscriptionSigned, $subscription);
        } catch (\Exception $e) {
            $msg .= ' ' . $e->getMessage();
            throw new SubscriptionSignatureException($subscriptionSigned, $msg);
        }

        /** @psalm-suppress NullableReturnStatement this is always a string */
        return $subscription;
    }

    /**
     * Execute implementation.
     *
     * @param array $data formdata
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
