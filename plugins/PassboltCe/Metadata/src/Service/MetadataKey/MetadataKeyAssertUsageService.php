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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service\MetadataKey;

use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\Validation\Validation;
use InvalidArgumentException;

class MetadataKeyAssertUsageService
{
    use LocatorAwareTrait;

    /**
     * @param string $metadataKeyId key uuid
     * @param bool $assertKeyId assert key id default true
     * @return void
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid
     */
    private function assertKeyId(string $metadataKeyId, bool $assertKeyId = true): void
    {
        if ($assertKeyId && !Validation::uuid($metadataKeyId)) {
            throw new InvalidArgumentException(__('The metadata key ID should be a valid UUID.'));
        }
    }

    /**
     * @param \Cake\ORM\Query $query query on the table to perform the assertion of usage on
     * @param string $metadataKeyId key uuid
     * @param bool $assertKeyId assert key id default true
     * @return bool if some tags are using the metadata key
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid and assertKeyId true
     */
    private function isUsedByTable(Query $query, string $metadataKeyId, bool $assertKeyId = true): bool
    {
        $this->assertKeyId($metadataKeyId, $assertKeyId);

        return $query
                ->where(['metadata_key_id' => $metadataKeyId])
                ->all()
                ->count() > 0;
    }

    /**
     * @param string $metadataKeyId key uuid
     * @return bool if some items are using the metadata key
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid
     */
    public function isKeyInUse(string $metadataKeyId): bool
    {
        $this->assertKeyId($metadataKeyId);

        return $this->isUsedByResources($metadataKeyId, false)
            || $this->isUsedByFolders($metadataKeyId, false)
            || $this->isUsedByTags($metadataKeyId, false);
    }

    /**
     * @param string $metadataKeyId key uuid
     * @param bool $assertKeyId assert key id default true
     * @return bool if some folders are using the metadata key
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid and assertKeyId true
     */
    public function isUsedByResources(string $metadataKeyId, bool $assertKeyId = true): bool
    {
        $resources = $this->getTableLocator()->get('Resources')->find()->where(['Resources.deleted' => false]);

        return $this->isUsedByTable($resources, $metadataKeyId, $assertKeyId);
    }

    /**
     * @param string $metadataKeyId key uuid
     * @param bool $assertKeyId assert key id default true
     * @return bool if some folders are using the metadata key
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid and assertKeyId true
     */
    public function isUsedByFolders(string $metadataKeyId, bool $assertKeyId = true): bool
    {
        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $folders = $this->getTableLocator()->get('Passbolt/Folders.Folders')->find();

            return $this->isUsedByTable($folders, $metadataKeyId, $assertKeyId);
        }

        return false;
    }

    /**
     * @param string $metadataKeyId key uuid
     * @param bool $assertKeyId assert key id default true
     * @return bool if some tags are using the metadata key
     * @throws \InvalidArgumentException if $metadataKeyId is not a valid uuid and assertKeyId true
     */
    public function isUsedByTags(string $metadataKeyId, bool $assertKeyId = true): bool
    {
        if (Configure::read('passbolt.plugins.tags')) {
            $tags = $this->getTableLocator()->get('Passbolt/Tags.Tags')->find();

            return $this->isUsedByTable($tags, $metadataKeyId, $assertKeyId);
        }

        return false;
    }
}
