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
namespace Passbolt\Folders\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersIntegrationTestCase;

/**
 * @covers \App\Controller\Resources\ResourcesIndexController
 */
class ResourcesIndexControllerTest extends FoldersIntegrationTestCase
{
    public function testResourcesIndexController_FilterHasParentSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        [$resourceA, $resourceB, $resourceC] = ResourceFactory::make(3)
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->persist();
        [$folderA, $folderB, $folderC] = FolderFactory::make(3)
            ->withPermissionsFor([$user])
            ->persist();
        [$resourceD, $resourceE] = ResourceFactory::make(2)
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user], $folderB)
            ->persist();
        $resourceF = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user], $folderC)
            ->persist();

        $cases = [
            'When has parent is false' => [
                'filter' => [false],
                'expected' => [
                    $resourceA->get('id'),
                    $resourceB->get('id'),
                    $resourceC->get('id'),
                ],
            ],
            'When has parent is false as a string' => [
                'filter' => ['false'],
                'expected' => [
                    $resourceA->get('id'),
                    $resourceB->get('id'),
                    $resourceC->get('id'),
                ],
            ],
            'When has-parent is single and return only 1 item' => [
                'filter' => [
                    $folderC->id,
                ],
                'expected' => [
                    $resourceF->get('id'),
                ],
            ],
            'When has-parent is single and return more than 1 item' => [
                'filter' => [
                    $folderB->id,
                ],
                'expected' => [
                    $resourceD->get('id'),
                    $resourceE->get('id'),
                ],
            ],
            'When has-parent is multiple and return 1 item' => [
                'filter' => [
                    $folderA->id, // has no children
                    $folderC->id, // has 1 child
                ],
                'expected' => [
                    $resourceF->get('id'),
                ],
            ],
            'When has-parent is multiple and return more than 1 item' => [
                'filter' => [
                    $folderB->id, // has 1 child
                    $folderC->id, // has 2 children
                ],
                'expected' => [
                    $resourceF->get('id'),
                    $resourceE->get('id'),
                    $resourceD->get('id'),
                ],
            ],
            'When has-parent is mixed with root and ids' => [
                'filter' => [
                    false, // has no children
                    $folderC->id,
                ],
                'expected' => [
                    $resourceF->get('id'),
                    $resourceA->get('id'),
                    $resourceB->get('id'),
                    $resourceC->get('id'),
                ],
            ],
        ];

        $this->logInAs($user);

        foreach ($cases as $case) {
            $queryParameters = http_build_query([
                'api-version' => 2,
                'filter' => [
                    'has-parent' => $case['filter'],
                ],
            ]);

            $this->getJson('/resources.json?' . $queryParameters);
            $this->assertSuccess();

            $resultFolderIds = Hash::extract($this->_responseJsonBody, '{n}.id');

            foreach ($case['expected'] as $expectedFolderChildrenId) {
                $this->assertContains($expectedFolderChildrenId, $resultFolderIds, 'Expected children is missing for the given parent folder.');
            }
        }
    }

    public function testResourcesIndexController_Personal_Should_Be_Unset_If_Null()
    {
        $user = $this->logInAsUser();
        ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $this->getJson('/resources.json');

        $result = (array)$this->_responseJsonBody[0];
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayNotHasKey(FolderizableBehavior::PERSONAL_PROPERTY, $result);
    }

    public function testResourcesIndexController_FilterHasParentWithFolderAndGroup()
    {
        $ada = UserFactory::make()->user()->persist();
        $betty = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$ada])->withGroupsUsersFor([$betty])->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$group], Permission::READ)->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withFoldersRelationsFor([$ada, $betty], $folder)
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        // login with Ada
        $this->logInAs($ada);

        $this->getJson('/resources.json?filter[has-parent]=' . $folder->get('id'));

        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $expectedResourceIds = [$resource1->get('id'), $resource2->get('id')];
        $this->assertEqualsCanonicalizing($expectedResourceIds, [$response[0]['id'], $response[1]['id']]);
    }
}
