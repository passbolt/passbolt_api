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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Test\TestCase\Model\Validation;

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchRotateKeyValidationService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use stdClass;

class MetadataResourcesBatchValidationServiceTest extends TestCase
{
    use TruncateDirtyTables;

    protected MetadataResourcesBatchRotateKeyValidationService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MetadataResourcesBatchRotateKeyValidationService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testMetadataResourcesBatchValidationService_Success(): void
    {
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->setField('metadata', 'foo')
            ->with('MetadataKeys', MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey())
            ->persist();

        $requestData = [[
            'id' => $resource->id,
            'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
            'metadata' => 'bar',
            'metadata_key_id' => $resource->metadata_key_id,
            'modified' => DateTime::now(),
            'modified_by' => UuidFactory::uuid(),
        ]];

        $result = $this->service->validateMany($requestData);

        $this->assertSame('bar', $result[0]['metadata']);
    }

    public function testMetadataResourcesBatchValidationService_ResourceNotFound(): void
    {
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->with('MetadataKeys')
            ->persist();

        $resourceId = UuidFactory::uuid();
        $requestData = [[
            'id' => $resourceId,
            'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
            'metadata' => 'bar',
            'metadata_key_id' => $resource->metadata_key_id,
            'modified' => DateTime::now(),
            'modified_by' => UuidFactory::uuid(),
        ]];

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Entity {$resourceId} not found.");
        $this->service->validateMany($requestData);
    }

    public static function metadataResourcesBatchValidationServiceInvalidRequestDataValuesProvider(): array
    {
        return [
            [
                ['🔥'],
                [new stdClass()],
                [[]],
                [['id' => 'not-a-valid-uuid']],
            ],
        ];
    }

    /**
     * @dataProvider metadataResourcesBatchValidationServiceInvalidRequestDataValuesProvider
     * @param array $requestData Invalid data.
     * @return void
     */
    public function testMetadataResourcesBatchValidationService_Error_InvalidRequestDataValues(array $requestData): void
    {
        $this->expectException(BadRequestException::class);
        $this->service->validateMany($requestData);
    }
}
