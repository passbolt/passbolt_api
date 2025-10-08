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
 * @since         5.6.0
 */
namespace Passbolt\Metadata\Service\Datacheck;

use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Entity\MetadataKey;

class MetadataKeyHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'MetadataKeys';
    public const CHECK_NO_METADATA_PRIVATE_KEYS = 'Check metadata private keys present';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->checks[self::CHECK_NO_METADATA_PRIVATE_KEYS] = $this->healthcheckFactory(self::CHECK_NO_METADATA_PRIVATE_KEYS, true); // phpcs:ignore
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $metadataKeysQuery = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys')->find();
        $records = $metadataKeysQuery
            ->contain(['MetadataPrivateKeys'])
            ->where([$metadataKeysQuery->newExpr()->isNull('MetadataKeys.deleted')])
            ->all();

        foreach ($records as $record) {
            $this->checkMetadataPrivateKeys($record);
        }

        return $this->getHealthchecks();
    }

    /**
     * Check that metadata key has metadata private keys.
     *
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey Metadata key entity to check.
     * @return void
     */
    private function checkMetadataPrivateKeys(MetadataKey $metadataKey): void
    {
        $metadataPrivateKeys = $metadataKey->metadata_private_keys;

        if (empty($metadataPrivateKeys)) {
            $msg = __('No metadata private keys found for metadata key {0}.', $metadataKey->id);
            $this->checks[self::CHECK_NO_METADATA_PRIVATE_KEYS]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_NO_METADATA_PRIVATE_KEYS]
                ->addDetail(__('Metadata keys present for metadata key {0}', $metadataKey->id), Healthcheck::STATUS_SUCCESS); // phpcs:ignore
        }
    }
}
