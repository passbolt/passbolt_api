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
 * @since         2.13.0
 */
namespace App\Service\Secrets;

use App\Model\Entity\Secret;
use App\Model\Table\SecretsTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SecretsHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Secrets';
    public const CHECK_VALIDATES = 'Can validate';
    public const CHECK_ENCRYPTED_FOR_RECIPIENT = 'Is encrypted for its recipient';

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private SecretsTable $table;

    private SecretRecipientValidationService $recipientValidationService;

    /**
     * Secret Healthcheck constructor.
     *
     * @param \App\Model\Table\SecretsTable|null $table secret table
     */
    public function __construct(?SecretsTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Secrets');
        $this->recipientValidationService = new SecretRecipientValidationService();
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
        $this->checks[self::CHECK_ENCRYPTED_FOR_RECIPIENT] = $this->healthcheckFactory(
            self::CHECK_ENCRYPTED_FOR_RECIPIENT,
            true,
        );
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $recordIds = $this->table->find()
            ->all()
            ->toArray();
        $recordIds = Hash::extract($recordIds, '{n}.id');

        foreach ($recordIds as $id) {
            $secret = $this->table->get($id);
            $this->canValidate($secret);

            $isSoftDeleted = $secret->deleted !== null;
            if (!$isSoftDeleted) {
                $this->isEncryptedForRecipient($secret);
            }
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Secret $secret secret
     * @return void
     */
    private function canValidate(Secret $secret): void
    {
        $copy = $this->table->newEntity($secret->toArray(), ['validate' => 'healthcheck']);
        if ($copy->getErrors()) {
            $msg = __('Validation failed for secret {0}. {1}', $secret->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for secret {0}', $secret->id), Healthcheck::STATUS_SUCCESS);
        }
    }

    /**
     * Checks that the secret is encrypted for its intended user.
     *
     * @param \App\Model\Entity\Secret $secret secret
     * @return void
     */
    private function isEncryptedForRecipient(Secret $secret): void
    {
        $check = $this->checks[self::CHECK_ENCRYPTED_FOR_RECIPIENT];
        $isEncryptedForUser = $this->recipientValidationService->isEncryptedForUser($secret->data, $secret->user_id);

        if ($isEncryptedForUser) {
            $msg = __('Secret {0} is encrypted for its recipient.', $secret->id);
            $check->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        } else {
            $msg = __(
                'Secret {0} (resource {1}, user {2}) is not encrypted for the user\'s current OpenPGP key.',
                $secret->id,
                $secret->resource_id,
                $secret->user_id,
            );
            $check->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        }
    }
}
