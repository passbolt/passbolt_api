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

use App\Service\Favorites\FavoritesDeleteService;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @see \App\Service\Favorites\FavoritesDeleteService
 */
class FavoritesDeleteServiceTest extends TestCase
{
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    /**
     * @var \App\Service\Favorites\FavoritesDeleteService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new FavoritesDeleteService();
    }

    public function testFavoritesDeleteService_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $favorite = FavoriteFactory::make()->setUser($user)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');

        $this->service->delete($favoriteId, $user->get('id'));

        $favorite = FavoriteFactory::find()
            ->where(['id' => $favoriteId])
            ->first();
        $this->assertNull($favorite);
    }

    public function testFavoritesDeleteService_ErrorValidationResourceDoesNotExist()
    {
        $userId = UuidFactory::uuid();
        $favoriteId = UuidFactory::uuid(); // This doesn't exist in DB, of course.

        $this->expectException(NotFoundException::class);
        $this->expectExceptionCode(404);

        $this->service->delete($favoriteId, $userId);
    }

    public function testFavoritesDeleteService_ErrorValidationResourceAccessDenied()
    {
        $user = UserFactory::make()->user()->persist();
        $owner = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($owner)->persist();
        $favorite = FavoriteFactory::make()->setUser($owner)->setResource($resource)->persist();
        $favoriteId = $favorite->get('id');

        $this->expectException(NotFoundException::class);
        $this->expectExceptionCode(404);

        $this->service->delete($favoriteId, $user->get('id'));
    }
}
