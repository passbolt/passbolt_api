<?php
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

namespace App\Test\TestCase\Component;

use App\Controller\Component\QueryStringComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Exception\Exception;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

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

    public function setUp()
    {
        $this->registryMock = $this->createMock(ComponentRegistry::class);

        $this->sut = new QueryStringComponent($this->registryMock);
        parent::setUp();
    }

    public function testThatValidateFiltersThrowExceptionIfNoValidationRuleIsDefinedForFilter()
    {
        $filterName = 'non-existing-filter';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('No validation rule for filter %s. Please create one.', $filterName));

        $this->sut::validateFilters([$filterName => '']);
    }

    public function testThatValidateFiltersThrowExceptionIfValidationCallbackFailed()
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

    public function testThatValidateFiltersReturnTrueIfValidationIsSuccessfulWithValidationCallback()
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
}
