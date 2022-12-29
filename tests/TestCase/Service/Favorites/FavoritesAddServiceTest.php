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
 * @since         3.9.0
 */

namespace App\Test\TestCase\Service\Favorites;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Favorite;
use App\Service\Favorites\FavoritesAddService;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @see \App\Service\Favorites\FavoritesAddService
 */
class FavoritesAddServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    /**
     * @var \App\Service\Favorites\FavoritesAddService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new FavoritesAddService();
    }

    public function testFavoritesAddService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $result = $this->service->add($uac, $resource->get('id'));

        $this->assertInstanceOf(Favorite::class, $result);
        $this->assertEquals($resource->get('id'), $result->foreign_key);
        $this->assertEquals('Resource', $result->foreign_model);
        // Assert entity has been persisted in the DB
        $favorite = FavoriteFactory::find()
            ->where(['foreign_key' => $resource->get('id')])
            ->first();
        $this->assertEquals($result->id, $favorite->id);
    }

    public function testFavoritesAddService_ErrorValidationInvalidUuid()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = 'invalid-id';
        $uac = $this->makeUac($user);

        $this->expectException(ValidationException::class);

        $this->service->add($uac, $resourceId);
    }

    public function testFavoritesAddService_ErrorValidationResourceDoesNotExist()
    {
        $user = UserFactory::make()->user()->persist();
        $resourceId = UuidFactory::uuid(); // This doesn't exist in DB, of course.
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);

        $this->service->add($uac, $resourceId);
    }

    public function testFavoritesAddService_ErrorValidationDeletedResource()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->setDeleted()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);

        $this->service->add($uac, $resource->get('id'));
    }

    public function testFavoritesAddService_ErrorValidationResourceAccessDenied()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);

        $this->service->add($uac, $resource->get('id'));
    }

    public function testFavoritesAddService_ErrorValidationAlreadyMarkedAsFavorite()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);
        // Create favorite beforehand so duplication can occur.
        FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();

        $this->expectException(BadRequestException::class);

        $this->service->add($uac, $resource->get('id'));
    }
}
