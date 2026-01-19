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
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;

class ResourcesHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Resources';
    public const CHECK_VALIDATES = 'Can validate';
    public const CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE = 'Is metadata key exist and active';

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private ResourcesTable $table;

    /**
     * Resources Healthcheck constructor.
     *
     * @param \App\Model\Table\ResourcesTable|null $table secret table
     */
    public function __construct(?ResourcesTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Resources');
        $this->checks = [
            self::CHECK_VALIDATES => $this->healthcheckFactory(self::CHECK_VALIDATES, true),
            self::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE => $this->healthcheckFactory(self::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE, true), // phpcs:ignore
        ];
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->table->find()->all();
        $metadataKeys = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys')
            ->find('active')
            ->all()
            ->indexBy('id')
            ->toArray();

        foreach ($records as $record) {
            $this->canValidate($record);
            $this->isMetadataKeyExistAndActive($record, $metadataKeys);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Resource $resource resource
     * @return void
     */
    private function canValidate(Resource $resource): void
    {
        $metadataResourceDto = MetadataResourceDto::fromArray($resource->toArray());

        $options = $metadataResourceDto->isV5() ? ['validate' => 'v5'] : [];
        $copy = $this->table->newEntity($resource->toArray(), $options);
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

    /**
     * Checks given resource has metadata key present and is active.
     *
     * Deleted resources are ignored as they might not be associated with an active metadata key
     *
     * @param \App\Model\Entity\Resource $resource resource
     * @return void
     */
    private function isMetadataKeyExistAndActive(Resource $resource, array $metadataKeys): void
    {
        if ($resource->deleted) {
            return;
        }
        $metadataResourceDto = MetadataResourceDto::fromArray($resource->toArray());
        if (!$metadataResourceDto->isV5() || $resource->metadata_key_type !== MetadataKey::TYPE_SHARED_KEY) {
            return;
        }

        $metadataKeyExists = array_key_exists($resource->metadata_key_id, $metadataKeys);
        if ($metadataKeyExists) {
            $msg = __('Metadata key exist and present for resource {0}.', $resource->id);
            $this->checks[self::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        } else {
            $msg = __('Metadata key does not exists or soft-deleted for resource {0}.', $resource->id);
            $this->checks[self::CHECK_IS_METADATA_KEY_EXIST_AND_ACTIVE]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR); // phpcs:ignore
        }
    }
}
