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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\QueryStringComponent;
use App\Utility\UuidFactory;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

class QueryStringComponentTest extends TestCase
{
    /**
     * @var MockObject|ComponentRegistry
     */
    private $registryMock;

    /**
     * @var QueryStringComponent
     */
    private $sut;

    public function setUp(): void
    {
        $this->registryMock = $this->createMock(ComponentRegistry::class);

        $this->sut = new QueryStringComponent($this->registryMock);
        parent::setUp();
    }

    public function testQueryStringComponent_ValidateFiltersError_NoValidationRuleDefined()
    {
        $filterName = 'non-existing-filter';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('No validation rule for filter %s. Please create one.', $filterName));

        $this->sut::validateFilters([$filterName => '']);
    }

    public function testQueryStringComponent_ValidateFiltersError_ValidationCallbackFailed()
    {
        $filterName = 'filter-with-validation-callback';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('Filter %s is not valid.', $filterName));

        $this->sut::validateFilters(
            [$filterName => ''],
            [$filterName => function ($value) {
                return false;
            }]
        );
    }

    public function testQueryStringComponent_ValidateFiltersSuccess()
    {
        $filterName = 'filter-with-validation-callback';

        $isValid = $this->sut::validateFilters(
            [$filterName => ''],
            [$filterName => function ($value) {
                return true;
            }]
        );

        $this->assertTrue($isValid);
    }

    public function testQueryStringComponent_validateFilterDateTime()
    {
        $filterName = 'datetime-param';
        $successTestCases = [
            'Global date time form Y-m-d' => '1970-01-01',
            'Global date time form Y-m-dZH:i:m' => '1970-01-01Z00:00:00',
        ];
        $errorTestCases = [
            'Random string' => 'invalid date time',
            'Integer' => 42,
            'Boolean' => true,
        ];

        foreach ($successTestCases as $testCaseName => $testCaseValue) {
            $isValid = $this->sut::validateFilterDateTime($testCaseValue, $filterName);
            $this->assertTrue($isValid, __('The case {0} should validate', $testCaseName));
        }

        foreach ($errorTestCases as $testCaseName => $testCaseValue) {
            try {
                $this->sut::validateFilterDateTime($testCaseValue, $filterName);
                $this->assertFalse(true, __('The case {0} should not validate', $testCaseName));
            } catch (\Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testQueryStringComponent_validateFilterInteger()
    {
        $filterName = 'integer-param';
        $successTestCases = [
            'Integer' => 42,
        ];
        $errorTestCases = [
            'Random string' => 'invalid date time',
            'String' => '42',
            'Float' => 42.2,
            'Boolean' => true,
        ];

        foreach ($successTestCases as $testCaseName => $testCaseValue) {
            $isValid = $this->sut::validateFilterInteger($testCaseValue, $filterName);
            $this->assertTrue($isValid, __('The case {0} should validate', $testCaseName));
        }

        foreach ($errorTestCases as $testCaseName => $testCaseValue) {
            try {
                $this->sut::validateFilterInteger($testCaseValue, $filterName);
                $this->assertFalse(true, __('The case {0} should not validate', $testCaseName));
            } catch (\Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testQueryStringComponent_normalizeInteger()
    {
        $successTestCases = [
            'Integer string' => '42',
            'Float string' => '42.2',
            'Integer' => 42,
            'Float' => 42.2,
        ];
        $errorTestCases = [
            'Random string' => 'invalid date time',
            'Array' => [],
        ];

        foreach ($successTestCases as $testCaseName => $testCaseValue) {
            $normalizedValue = $this->sut::normalizeInteger($testCaseValue);
            $this->assertEquals(42, $normalizedValue, __('The case {0} is not normalized as expected', $testCaseName));
        }

        foreach ($errorTestCases as $testCaseName => $testCaseValue) {
            $normalizedValue = $this->sut::normalizeInteger($testCaseValue);
            $this->assertTrue($normalizedValue === 0 || $normalizedValue === false);
        }
    }

    public function testQueryStringComponent_extractQueryArrayItems()
    {
        $expected = [
            'filter' => [
                'has-users' => ['user1', 'user2'],
            ],
            'contain' => [
                'users' => 1,
            ],
        ];
        $query = [
            'filter' => [
                'has-users' => 'user1,user2',
            ],
            'contain' => [
                'users' => 1,
            ],
        ];
        $actual = QueryStringComponent::extractQueryArrayItems($query);
        $this->assertEquals($expected, $actual);
    }

    public function testQueryStringComponent_rewriteLegacyItems()
    {
        $expected = [
            'filter' => [
                'modified-after' => 'yesterday',
                'search' => 'test',
            ],
            'contain' => [
                'last_logged_in' => 1,
            ],
        ];
        $query = [
            'modified_after' => 'yesterday',
            'keywords' => 'test',
            'contain' => [
                'LastLoggedIn' => 1,
            ],
        ];
        $actual = QueryStringComponent::rewriteLegacyItems($query);
        $this->assertEquals($expected, $actual);
    }

    public function dataForTestQueryStringComponentFilters(): array
    {
        $fooId = UuidFactory::uuid('foo');
        $barId = UuidFactory::uuid('bar');
        $bazId = UuidFactory::uuid('baz');

        return [
            [[], "filter[has-groups]=$fooId", []], // No allowed query items
            [BadRequestException::class, 'filter[has-groups]=foo',], // not a valid uuid
            [['has-groups' => [$fooId]], "filter[has-groups]=$fooId",], // string assignment
            [['has-groups' => [$fooId, $barId]], "filter[has-groups]=$fooId,$barId",], // comma separated assignment
            [['has-groups' => [$fooId, $barId]], "filter[has-groups][]=$fooId&filter[has-groups][]=$barId",], // array assignment
            [['has-groups' => [$barId]], "filter[has-groups][]=$fooId&filter[has-groups]=$barId",], // array + string assignment
            [['has-groups' => [$barId]], "filter[has-groups]=$fooId&filter[has-groups][]=$barId",], // string + array assignment
            [['has-groups' => [$bazId]], "filter[has-groups]=$fooId&filter[has-groups]=$barId&filter[has-groups]=$bazId",], // string + string + string assignment
            [['has-groups' => [$bazId]], "filter[has-groups]=$fooId,$barId&filter[has-groups]=$bazId",], // comma separated + string assignment
            [['has-groups' => [$barId,$bazId]], "filter[has-groups]=$fooId&filter[has-groups]=$barId,$bazId",], //  string + comma separated assignment
            [BadRequestException::class, "filter[has-groups][]=$fooId,$barId",], // comma separated in array assignment
            [BadRequestException::class, "filter[has-groups][]=$fooId&filter[has-groups][]=$barId,$bazId",], // array + comma separated in array assignment
        ];
    }

    /**
     * @dataProvider dataForTestQueryStringComponentFilters
     */
    public function testQueryStringComponentFilters($expected, string $query, array $allowedQueryItems = ['filter' => ['has-groups']], array $filterValidators = [])
    {
        $url = '/?' . $query;

        $request = new ServerRequest(compact('url'));
        $response = new Response();
        $controller = new Controller($request, $response);
        $registry = new ComponentRegistry($controller);
        $component = new QueryStringComponent($registry);

        $expectException = is_string($expected);
        if ($expectException) {
            $this->expectException($expected);
        }
        $query = $component->get($allowedQueryItems, $filterValidators);
        if (!$expectException) {
            $expected = empty($allowedQueryItems) ? [] : ['filter' => $expected];
            $this->assertSame($expected, $query);
        }
    }
}
