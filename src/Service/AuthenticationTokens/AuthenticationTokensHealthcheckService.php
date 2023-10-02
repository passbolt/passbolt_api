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
namespace App\Service\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Model\Table\AuthenticationTokensTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class AuthenticationTokensHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'AuthenticationTokens';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    private $AuthenticationTokens;

    /**
     * AuthenticationTokens Healthcheck constructor.
     *
     * @param \App\Model\Table\AuthenticationTokensTable|null $table table
     */
    public function __construct(?AuthenticationTokensTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->AuthenticationTokens = $table ?? TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->AuthenticationTokens
            ->find()
            ->where(['type IN ' => AuthenticationTokensTable::ALLOWED_TYPES])
            ->all();

        foreach ($records as $i => $record) {
            $this->canValidate($record);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\AuthenticationToken $authenticationToken authenticationToken
     * @return void
     */
    private function canValidate(AuthenticationToken $authenticationToken): void
    {
        $copy = $this->AuthenticationTokens->newEntity($authenticationToken->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $jsonErr = json_encode($copy->getErrors());
            $msg = __('Validation failed for authenticationToken {0}. {1}', $authenticationToken->id, $jsonErr);
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $msg = __('Validation success for authenticationToken {0}', $authenticationToken->id);
            $this->checks[self::CHECK_VALIDATES]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        }
    }
}
