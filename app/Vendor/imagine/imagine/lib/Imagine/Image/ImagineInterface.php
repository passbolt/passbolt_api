<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Image;

use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Exception\RuntimeException;

/**
 * The imagine interface
 */
interface ImagineInterface
{
    const VERSION = '0.3.0';

    /**
     * Creates a new empty image with an optional background color
     *
     * @param BoxInterface   $size
     * @param ColorInterface $color
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return ImageInterface
     */
    public function create(BoxInterface $size, ColorInterface $color = null);

    /**
     * Opens an existing image from $path
     *
     * @param string $path
     *
     * @throws RuntimeException
     *
     * @return ImageInterface
     */
    public function open($path);

    /**
     * Loads an image from a binary $string
     *
     * @param string $string
     *
     * @throws RuntimeException
     *
     * @return ImageInterface
     * @return ImageInterface
     */
    public function load($string);

    /**
     * Loads an image from a resource $resource
     *
     * @param resource $resource
     *
     * @throws RuntimeException
     *
     * @return ImageInterface
     */
    public function read($resource);

    /**
     * Constructs a font with specified $file, $size and $color
     *
     * The font size is to be specified in points (e.g. 10pt means 10)
     *
     * @param string         $file
     * @param integer        $size
     * @param ColorInterface $color
     *
     * @return FontInterface
     */
    public function font($file, $size, ColorInterface $color);
}
