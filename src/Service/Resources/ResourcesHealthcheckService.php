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
namespace App\Service\Resources;

use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class ResourcesHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Resources';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $table;

    /**
     * Resources Healthcheck constructor.
     *
     * @param \App\Model\Table\ResourcesTable|null $table secret table
     */
    public function __construct(?ResourcesTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Resources');
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
     * @param \App\Model\Entity\Resource $resource resource
     * @return void
     */
    private function canValidate(Resource $resource)
    {
        $copy = $this->table->newEntity($resource->toArray());
        $error = $copy->getErrors();

        // Ignore secrets and permissions
        unset($error['permissions']);
        unset($error['secrets']);

        if (count($error)) {
            $msg = __('Validation failed for resource {0}. {1}', $resource->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for resource {0}', $resource->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
