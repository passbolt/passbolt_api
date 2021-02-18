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
namespace App\Service\Favorites;

use App\Model\Entity\Favorite;
use App\Model\Table\FavoritesTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class FavoritesHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Favorites';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\FavoritesTable
     */
    private $table;

    /**
     * Favorites Healthcheck constructor.
     *
     * @param \App\Model\Table\FavoritesTable|null $table secret table
     */
    public function __construct(?FavoritesTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Favorites');
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
     * @param \App\Model\Entity\Favorite $favorite favorite
     * @return void
     */
    private function canValidate(Favorite $favorite): void
    {
        $copy = $this->table->newEntity($favorite->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $msg = __('Validation failed for favorite {0}. {1}', $favorite->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for favorite {0}', $favorite->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
