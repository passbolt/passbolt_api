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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Table;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class UiActionsTableTest extends RbacsTestCase
{
    use FormatValidationTrait;

    /**
     * @var \Passbolt\Rbacs\Model\Table\UiActionsTable
     */
    public $UiActions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->UiActions = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.UiActions');
    }

    /**
     * Get default entity options.
     */
    public function getDummyEntityDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    public function testUiActionsTable_ValidationName(): void
    {
        $testCases = [
            'ascii' => self::getAsciiTestCases(255),
        ];
        $data = UiActionFactory::make()->getEntity()->toArray();
        $this->assertFieldFormatValidation($this->UiActions, 'name', $data, self::getDummyEntityDefaultOptions(), $testCases);
    }
}
