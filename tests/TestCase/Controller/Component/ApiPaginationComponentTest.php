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
 * @since         3.1.0
 */

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ApiPaginationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

class ApiPaginationComponentTest extends TestCase
{
    /**
     * @dataProvider dataForTestParseQuery
     * @param string $url
     * @param array $expectedSort
     */
    public function testParseQuery(string $url, array $expectedSort): void
    {
        $request = new ServerRequest(compact('url'));
        $controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('getRequest')->willReturn($request);
        $controller->paginate = [
            'sortableFields' => [
                'Resources.username',
                'Resources.modified',
            ],
        ];

        $registry = new ComponentRegistry($controller);
        $component = new ApiPaginationComponent($registry);

        $result = $component->parseQuery();

        $this->assertSame($expectedSort, $result);
    }

    public function dataForTestParseQuery(): array
    {
        return [
            ['/foo', []],

            // Pagination
            ['/foo?sort=Resources.modified', ['Resources.modified' => 'asc']],
            ['/foo?sort=Resources.modified&direction=asc', ['Resources.modified' => 'asc']],
            ['/foo?sort=Resources.modified&direction=desc', ['Resources.modified' => 'desc']],
            ['/foo?sort[Resources.modified]=asc', ['Resources.modified' => 'asc']],
            ['/foo?sort[Resources.modified]=desc', ['Resources.modified' => 'desc']],
            ['/foo?sort[Resources.modified]=desc&direction=asc', ['Resources.modified' => 'desc']],
            ['/foo?sort[Resources.modified]=desc&sort[Resources.username]', ['Resources.modified' => 'desc', 'Resources.username' => 'asc']],
            ['/foo?sort[Resources.modified]=desc&sort[Resources.username]=desc', ['Resources.modified' => 'desc', 'Resources.username' => 'desc']],

            // Legacy pagination
            ['/foo?order=Resources.modified', ['Resources.modified' => 'asc']],
            ['/foo?order=Resources.modified ASC', ['Resources.modified' => 'asc']],
            ['/foo?order=Resources.modified DESC', ['Resources.modified' => 'desc']],
            ['/foo?order[]=Resources.modified DESC', ['Resources.modified' => 'desc']],
            ['/foo?order[]=Resources.modified DESC&order[]=Resources.username', ['Resources.modified' => 'desc', 'Resources.username' => 'asc']],
            ['/foo?order[]=Resources.modified DESC&order[]=Resources.username DESC', ['Resources.modified' => 'desc', 'Resources.username' => 'desc']],
        ];
    }
}
