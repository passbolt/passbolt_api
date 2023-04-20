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
 * @since         3.0.0
 */

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Table\AvatarsTable;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Laminas\Diactoros\UploadedFile;

/**
 * App\Model\Table\FileStorageTable Test Case
 */
class AvatarsValidationTest extends TestCase
{
    use AvatarsModelTrait;
    use TruncateDirtyTables;

    /**
     * Test subject
     *
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Avatars') ? [] : ['className' => AvatarsTable::class];
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Avatars);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertSame('avatars', $this->Avatars->getTable());
    }

    public function dataProviderForValidationDefaultSuccess(): array
    {
        return [
            ['png'],
            ['jpg'],
            ['jpeg'],
            ['gif'],
        ];
    }

    /**
     * Test validationDefault method
     *
     * @dataProvider dataProviderForValidationDefaultSuccess
     * @return void
     */
    public function testValidationDefaultSuccess(string $extension)
    {
        $file = new UploadedFile(
            FIXTURES . 'Avatar' . DS . 'ada.' . $extension,
            170049,
            \UPLOAD_ERR_OK,
            'ada.' . $extension,
            'image/' . $extension
        );

        $file = $this->Avatars->newEntity(compact('file'));
        $this->assertEmpty($file->getErrors());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefaultError()
    {
        for ($errorStatus = 1; $errorStatus <= 8; $errorStatus++) {
            $file = new UploadedFile(
                FIXTURES . 'Avatar' . DS . 'ada.png',
                170049,
                $errorStatus,
                'ada.png',
                'image/png'
            );
        }

        $file = $this->Avatars->newEntity(compact('file'));
        $this->assertNotEmpty($file->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRulesOnNonExistingProfile()
    {
        $data = [
            'file' => $this->createUploadFile(),
            'profile_id' => UuidFactory::uuid(),
        ];
        $avatar = $this->Avatars->newEntity($data);

        $this->expectException(PersistenceFailedException::class);
        $this->Avatars->saveOrFail($avatar);
    }
}
