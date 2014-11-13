<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Effects;

use Imagine\Exception\RuntimeException;
use Imagine\Image\Palette\Color\ColorInterface;

/**
 * Interface for the effects
 */
interface EffectsInterface
{
    /**
     * Apply gamma correction
     *
     * @param  float            $correction
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function gamma($correction);

    /**
     * Invert the colors of the image
     *
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function negative();

    /**
     * Grayscale the image
     *
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function grayscale();

    /**
     * Colorize the image
     *
     * @param ColorInterface $color
     *
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function colorize(ColorInterface $color);

    /**
     * Sharpens the image
     *
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function sharpen();

    /**
     * Blur the image
     *
     * @param float|int $sigma
     *
     * @return EffectsInterface
     *
     * @throws RuntimeException
     */
    public function blur($sigma);
}
