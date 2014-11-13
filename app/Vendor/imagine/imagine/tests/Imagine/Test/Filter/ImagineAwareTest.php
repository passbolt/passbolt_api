<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Filter;

use Imagine\Filter\Transformation;

/**
 * ImagineAwareTest.
 */
class ImagineAwareTest extends FilterTestCase
{
    /**
     * Test if filter works when passing Imagine instance directly.
     */
    public function testFilterWorksWhenPassedImagineAndCalledDirectly()
    {
        $imagineMock = $this->getImagineMock();

        $filter = new DummyImagineAwareFilter();
        $filter->setImagine($imagineMock);
        $image = $filter->apply($this->getImage());

        $this->assertInstanceOf('Imagine\\Image\\ImageInterface', $image);
    }

    /**
     * Test if filter works when passing Imagine instance via
     * Transformation.
     */
    public function testFilterWorksWhenPassedImagineViaTransformation()
    {
        $imagineMock = $this->getImagineMock();

        $filters = new Transformation($imagineMock);
        $filters->add(new DummyImagineAwareFilter());
        $image = $filters->apply($this->getImage());

        $this->assertInstanceOf('Imagine\\Image\\ImageInterface', $image);
    }

    /**
     * Test if filter throws exception when called directly without
     * passing Imagine instance.
     *
     * @expectedException \Imagine\Exception\InvalidArgumentException
     */
    public function testFilterThrowsExceptionWhenCalledDirectly()
    {
        $filter = new DummyImagineAwareFilter();
        $filter->apply($this->getImage());
    }

    /**
     * Test if filter throws exception via Transformation without
     * passing Imagine instance.
     *
     * @expectedException \Imagine\Exception\InvalidArgumentException
     */
    public function testFilterThrowsExceptionViaTransformation()
    {
        $filters = new Transformation();
        $filters->add(new DummyImagineAwareFilter());
        $filters->apply($this->getImage());
    }

    protected function getImagineMock()
    {
        $imagineMock = $this->getMock('Imagine\\Image\\ImagineInterface');
        $imagineMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->getImage()));

        return $imagineMock;
    }
}
