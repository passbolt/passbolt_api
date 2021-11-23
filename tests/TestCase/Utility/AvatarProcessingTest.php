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
namespace App\Test\TestCase\Utility;

use App\Utility\AvatarProcessing;
use App\Utility\Filesystem\DirectoryUtility;
use Cake\TestSuite\TestCase;

class AvatarProcessingTest extends TestCase
{
    /**
     * @var string Test data directory.
     */
    public $testDirectory = TMP . 'tests' . DS . 'avatar_processing' . DS;

    /**
     * @var string Fixture image directory.
     */
    public $fixtureDirectory = FIXTURES . 'Avatar' . DS;

    public function setUp(): void
    {
        DirectoryUtility::removeRecursively($this->testDirectory);
        mkdir($this->testDirectory);
    }

    public function imagesToProcess()
    {
        return [
            ['ada.png'],
            ['ada.jpg'],
            ['ada.gif'],
            ['50_80.png'],
            ['50_60.png'],
            ['100_50.gif'],
        ];
    }

    /**
     * @dataProvider imagesToProcess
     * @see AvatarProcessing::resizeAndCrop()
     */
    public function testAvatarCropAndResize(string $filename)
    {
        $width = 80;
        $height = 80;
        $stream = file_get_contents($this->fixtureDirectory . $filename);
        $processedStream = AvatarProcessing::resizeAndCrop(
            $stream,
            $width,
            $height
        );
        $ouputFilename = $this->testDirectory . $filename;
        file_put_contents($ouputFilename, $processedStream);

        $imageSize = getimagesize($ouputFilename);
        $this->assertSame($width, $imageSize[0]);
        $this->assertSame($height, $imageSize[1]);
        $this->assertSame('image/jpeg', $imageSize['mime']);
    }
}
