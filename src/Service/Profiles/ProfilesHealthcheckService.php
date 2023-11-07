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
namespace App\Service\Profiles;

use App\Model\Entity\Profile;
use App\Model\Table\ProfilesTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class ProfilesHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Profiles';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\ProfilesTable
     */
    private $table;

    /**
     * Profiles Healthcheck constructor.
     *
     * @param \App\Model\Table\ProfilesTable|null $table secret table
     */
    public function __construct(?ProfilesTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Profiles');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->table->find()->all();

        foreach ($records as $i => $record) {
            $this->canValidate($record);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Profile $profile profile
     * @return void
     */
    private function canValidate(Profile $profile): void
    {
        $copy = $this->table->newEntity($profile->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $msg = __('Validation failed for profile {0}. {1}', $profile->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for profile {0}', $profile->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
