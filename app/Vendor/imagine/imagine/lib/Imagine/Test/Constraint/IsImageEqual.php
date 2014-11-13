<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Constraint;

use Imagine\Image\ImageInterface;
use Imagine\Image\Histogram\Bucket;
use Imagine\Image\Histogram\Range;

class IsImageEqual extends \PHPUnit_Framework_Constraint
{
    /**
     * @var Imagine\Image\ImageInterface
     */
    private $value;

    /**
     * @var float
     */
    private $delta;

    /**
     * @var integer
     */
    private $buckets;

    /**
     * @param Imagine\Image\ImageInterface $value
     * @param float                        $delta
     * @param integer                      $buckets
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value, $delta = 0.1, $buckets = 4)
    {
        if (!$value instanceof ImageInterface) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(1, 'Imagine\Image\ImageInterface');
        }

        if (!is_numeric($delta)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(2, 'numeric');
        }

        if (!is_integer($buckets) || $buckets <= 0) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(3, 'integer');
        }

        $this->value   = $value;
        $this->delta   = $delta;
        $this->buckets = $buckets;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($other)
    {
        if (!$other instanceof ImageInterface) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(1, 'Imagine\Image\ImageInterface');
        }

        list($currentRed, $currentGreen, $currentBlue, $currentAlpha) = $this->normalize($this->value);
        list($otherRed, $otherGreen, $otherBlue, $otherAlpha)         = $this->normalize($other);

        $total = 0;

        foreach ($currentRed as $bucket => $count) {
            $total += abs($count - $otherRed[$bucket]);
        }

        foreach ($currentGreen as $bucket => $count) {
            $total += abs($count - $otherGreen[$bucket]);
        }

        foreach ($currentBlue as $bucket => $count) {
            $total += abs($count - $otherBlue[$bucket]);
        }

        foreach ($currentAlpha as $bucket => $count) {
            $total += abs($count - $otherAlpha[$bucket]);
        }

        return $total <= $this->delta;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('contains color histogram identical to expected %s', \PHPUnit_Util_Type::toString($this->value));
    }

    /**
     * @param Imagine\Image\ImageInterface $image
     *
     * @return array
     */
    private function normalize(ImageInterface $image)
    {
        $step    = (int) round(255 / $this->buckets);

        $red =
        $green =
        $blue =
        $alpha = array();

        for ($i = 1; $i <= $this->buckets; $i++) {
            $range   = new Range(($i - 1) * $step, $i * $step);
            $red[]   = new Bucket($range);
            $green[] = new Bucket($range);
            $blue[]  = new Bucket($range);
            $alpha[] = new Bucket($range);
        }

        foreach ($image->histogram() as $color) {
            foreach ($red as $bucket) {
                $bucket->add($color->getRed());
            }

            foreach ($green as $bucket) {
                $bucket->add($color->getGreen());
            }

            foreach ($blue as $bucket) {
                $bucket->add($color->getBlue());
            }

            foreach ($alpha as $bucket) {
                $bucket->add($color->getAlpha());
            }
        }

        $total = $image->getSize()->square();

        $callback = function (Bucket $bucket) use ($total) {
            return count($bucket) / $total;
        };

        return array(
            array_map($callback, $red),
            array_map($callback, $green),
            array_map($callback, $blue),
            array_map($callback, $alpha),
        );
    }
}
