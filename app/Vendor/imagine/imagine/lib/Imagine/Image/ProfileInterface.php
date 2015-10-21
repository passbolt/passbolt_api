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

interface ProfileInterface
{
    /**
     * Returns the name of the profile
     *
     * @return String
     */
    public function name();

    /**
     * Returns the profile data
     *
     * @return String
     */
    public function data();
}
