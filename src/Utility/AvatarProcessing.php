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
namespace App\Utility;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class AvatarProcessing
{
    public const JPEG_QUALITY = 90;

    /**
     * Resize and crop at center an image.
     *
     * @param string $content Content to process.
     * @param int $cropWidth Width in px.
     * @param int $cropHeight Height in px.
     * @return string Processed stream.
     */
    public static function resizeAndCrop(string $content, int $cropWidth, int $cropHeight): string
    {
        $imagine = new Imagine();

        $image = $imagine->load($content);
        $size = $image->getSize();

        // Resize the min width or height to fit the crop size
        $widthRatio = $size->getWidth() / $cropWidth; // < 1 if the image width is smaller than the crop
        $heightRatio = $size->getHeight() / $cropHeight; // < 1 if the image height is smaller than the crop
        $minRatio = min($widthRatio, $heightRatio); // Resize along the smallest ratio

        $newWidth = $size->getWidth() / $minRatio;
        $newHeight = $size->getHeight() / $minRatio;
        $image->resize(new Box($newWidth, $newHeight));

        // Crop that resized image
        $size = $image->getSize();
        $x = round(($size->getWidth() - $cropWidth) / 2);
        $y = round(($size->getHeight() - $cropHeight) / 2);

        return $image
            ->crop(new Point($x, $y), new Box($cropWidth, $cropHeight))
            ->get('jpeg', [
                'quality' => self::JPEG_QUALITY,
            ]);
    }
}
