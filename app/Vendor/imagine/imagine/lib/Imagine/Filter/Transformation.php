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
use Imagine\Filter\Basic\ApplyMask;
use Imagine\Filter\Basic\Copy;
use Imagine\Filter\Basic\Crop;
use Imagine\Filter\Basic\Fill;
use Imagine\Filter\Basic\FlipVertically;
use Imagine\Filter\Basic\FlipHorizontally;
use Imagine\Filter\Basic\Paste;
use Imagine\Filter\Basic\Resize;
use Imagine\Filter\Basic\Rotate;
use Imagine\Filter\Basic\Save;
use Imagine\Filter\Basic\Show;
use Imagine\Filter\Basic\Strip;
use Imagine\Filter\Basic\Thumbnail;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Fill\FillInterface;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\PointInterface;

/**
 * A transformation filter
 */
final class Transformation implements FilterInterface, ManipulatorInterface
{
    /**
     * @var array
     */
    private $filters = array();

    /**
     * @var array
     */
    private $sorted;

    /**
     * An ImagineInterface instance.
     *
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * Class constructor.
     *
     * @param ImagineInterface $imagine An ImagineInterface instance
     */
    public function __construct(ImagineInterface $imagine = null)
    {
        $this->imagine = $imagine;
    }

    /**
     * Applies a given FilterInterface onto given ImageInterface and returns
     * modified ImageInterface
     *
     * @param ImageInterface  $image
     * @param FilterInterface $filter
     *
     * @return ImageInterface
     * @throws InvalidArgumentException
     */
    public function applyFilter(ImageInterface $image, FilterInterface $filter)
    {
        if ($filter instanceof ImagineAware) {
            if ($this->imagine === null) {
                throw new InvalidArgumentException(sprintf('In order to use %s pass an Imagine\Image\ImagineInterface instance to Transformation constructor', get_class($filter)));
            }
            $filter->setImagine($this->imagine);
        }

        return $filter->apply($image);
    }

    /**
     * Returns a list of filters sorted by their priority. Filters with same priority will be returned in the order they were added.
     *
     * @return array
     */
    public function getFilters()
    {
        if (null === $this->sorted) {
            if (!empty($this->filters)) {
                ksort($this->filters);
                $this->sorted = call_user_func_array('array_merge', $this->filters);
            } else {
                $this->sorted = array();
            }
        }

        return $this->sorted;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(ImageInterface $image)
    {
        return array_reduce(
            $this->getFilters(),
            array($this, 'applyFilter'),
            $image
        );
    }

    /**
     * {@inheritdoc}
     */
    public function copy()
    {
        return $this->add(new Copy());
    }

    /**
     * {@inheritdoc}
     */
    public function crop(PointInterface $start, BoxInterface $size)
    {
        return $this->add(new Crop($start, $size));
    }

    /**
     * {@inheritdoc}
     */
    public function flipHorizontally()
    {
        return $this->add(new FlipHorizontally());
    }

    /**
     * {@inheritdoc}
     */
    public function flipVertically()
    {
        return $this->add(new FlipVertically());
    }

    /**
     * {@inheritdoc}
     */
    public function strip()
    {
        return $this->add(new Strip());
    }

    /**
     * {@inheritdoc}
     */
    public function paste(ImageInterface $image, PointInterface $start)
    {
        return $this->add(new Paste($image, $start));
    }

    /**
     * {@inheritdoc}
     */
    public function applyMask(ImageInterface $mask)
    {
        return $this->add(new ApplyMask($mask));
    }

    /**
     * {@inheritdoc}
     */
    public function fill(FillInterface $fill)
    {
        return $this->add(new Fill($fill));
    }

    /**
     * {@inheritdoc}
     */
    public function resize(BoxInterface $size, $filter = ImageInterface::FILTER_UNDEFINED)
    {
        return $this->add(new Resize($size, $filter));
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($angle, ColorInterface $background = null)
    {
        return $this->add(new Rotate($angle, $background));
    }

    /**
     * {@inheritdoc}
     */
    public function save($path = null, array $options = array())
    {
        return $this->add(new Save($path, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function show($format, array $options = array())
    {
        return $this->add(new Show($format, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function thumbnail(BoxInterface $size, $mode = ImageInterface::THUMBNAIL_INSET, $filter = ImageInterface::FILTER_UNDEFINED)
    {
        return $this->add(new Thumbnail($size, $mode, $filter));
    }

    /**
     * Registers a given FilterInterface in an internal array of filters for
     * later application to an instance of ImageInterface
     *
     * @param  FilterInterface $filter
     * @param  int             $priority
     * @return Transformation
     */
    public function add(FilterInterface $filter, $priority = 0)
    {
        $this->filters[$priority][] = $filter;
        $this->sorted = null;

        return $this;
    }
}
