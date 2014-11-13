<?php

namespace Imagine\Test\Filter;

use Imagine\Filter\ImagineAware;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * DummyImagineAwareFilter.
 */
class DummyImagineAwareFilter extends ImagineAware
{
    /**
     * Apply filter.
     *
     * @param  ImageInterface $image An ImageInterface instance
     * @return ImageInterface
     */
    public function apply(ImageInterface $image)
    {
        return $this->getImagine()->create(new Box(200, 200));
    }
}
