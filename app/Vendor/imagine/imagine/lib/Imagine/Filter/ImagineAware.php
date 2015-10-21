<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Filter;

use Imagine\Exception\InvalidArgumentException;
use Imagine\Image\ImagineInterface;

/**
 * ImagineAware base class
 */
abstract class ImagineAware implements FilterInterface
{
    /**
     * An ImagineInterface instance.
     *
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * Set ImagineInterface instance.
     *
     * @param ImagineInterface $imagine An ImagineInterface instance
     */
    public function setImagine(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * Get ImagineInterface instance.
     *
     * @return ImagineInterface
     *
     * @throws InvalidArgumentException
     */
    public function getImagine()
    {
        if (!$this->imagine instanceof ImagineInterface) {
            throw new InvalidArgumentException(sprintf('In order to use %s pass an Imagine\Image\ImagineInterface instance to filter constructor', get_class($this)));
        }

        return $this->imagine;
    }
}
