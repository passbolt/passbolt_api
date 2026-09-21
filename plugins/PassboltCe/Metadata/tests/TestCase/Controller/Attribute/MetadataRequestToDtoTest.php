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
 * @since         5.16.0
 */
namespace Passbolt\Metadata\Test\TestCase\Controller\Attribute;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Passbolt\Metadata\Controller\Attribute\MetadataRequestToDto;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use ReflectionFunction;
use ReflectionParameter;

/**
 * @covers \Passbolt\Metadata\Controller\Attribute\MetadataRequestToDto
 */
class MetadataRequestToDtoTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    /**
     * @param array $data Request body.
     * @param \App\Model\Entity\User $user Authenticated user.
     * @return \Cake\Http\ServerRequest
     */
    private function makeRequest(array $data, User $user): ServerRequest
    {
        $request = new ServerRequest([
            'environment' => ['REQUEST_METHOD' => 'POST'],
            'post' => $data,
        ]);

        return $request->withAttribute('identity', $user);
    }

    /**
     * @return \ReflectionParameter
     */
    private function makeDtoParameter(): ReflectionParameter
    {
        $action = function (MetadataResourceDto $resourceDto): void {
        };

        return (new ReflectionFunction($action))->getParameters()[0];
    }

    public function testMetadataRequestToDto_Resolve_Success_PopulatesMetadataKeyId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->withValidGpgKey()->persist();
        $request = $this->makeRequest([
            'metadata' => '-----BEGIN PGP MESSAGE-----',
            'metadata_key_type' => 'user_key',
        ], $user);

        $dto = (new MetadataRequestToDto())->resolve($this->makeDtoParameter(), $request);

        $this->assertInstanceOf(MetadataResourceDto::class, $dto);
        $this->assertSame($user->gpgkey->get('id'), $dto->toArray()['metadata_key_id']);
    }

    public function testMetadataRequestToDto_Resolve_Success_KeepsProvidedMetadataKeyId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->withValidGpgKey()->persist();
        $metadataKeyId = UuidFactory::uuid();
        $request = $this->makeRequest([
            'metadata' => '-----BEGIN PGP MESSAGE-----',
            'metadata_key_id' => $metadataKeyId,
            'metadata_key_type' => 'user_key',
        ], $user);

        $dto = (new MetadataRequestToDto())->resolve($this->makeDtoParameter(), $request);

        $this->assertSame($metadataKeyId, $dto->toArray()['metadata_key_id']);
    }

    public function testMetadataRequestToDto_Resolve_Success_V4PayloadUntouched(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->withValidGpgKey()->persist();
        $data = ['name' => 'A v4 resource', 'username' => 'ada@passbolt.com'];
        $request = $this->makeRequest($data, $user);

        $dto = (new MetadataRequestToDto())->resolve($this->makeDtoParameter(), $request);

        $this->assertFalse($dto->isV5());
        $this->assertSame($data, $dto->toArray());
    }

    public function testMetadataRequestToDto_Resolve_Error_IncompleteV5Payload(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->withValidGpgKey()->persist();
        $request = $this->makeRequest(['metadata' => '-----BEGIN PGP MESSAGE-----'], $user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Few fields are missing for the V5.');

        (new MetadataRequestToDto())->resolve($this->makeDtoParameter(), $request);
    }
}
