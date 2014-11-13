Drawing shapes on an image
==========================

Imagine also provides a fully-featured drawing API, inspired by Python's PIL.
To use the api, you need to get a drawer instance from you current image instance, using ``ImageInterface::draw()`` method.

Example
-------

.. code-block:: php

    <?php
    $palette = new Imagine\Image\Palette\RGB();

    $image = $imagine->create(new Box(400, 300), $palette->color('#000'));

    $image->draw()
        ->ellipse(new Point(200, 150), new Box(300, 225), $image->palette()->color('fff'));

    $image->save('/path/to/ellipse.png');

The above example would draw an ellipse on a black 400x300px image, of white color. It would place the ellipse in the center of the image, and set its larger radius to 300px, with a smaller radius of 225px. You could also make the ellipse filled,  by passing `true` as the last parameter

Text
----

As you've noticed from ``DrawerInterface::text()``, there is also ``Font`` class. This class is a simple value object, representing the font. To construct a font, you have to pass the ``$file`` string (path to font file), ``$size`` value (integer value, representing size points) and ``$color`` (``Imagine\Image\Palette\Color\ColorInterface`` instance). After you have a font instance, you can use one of its three methods to inspect any of the values it's been constructed with:

* ``->getFile()`` - returns font file path

* ``->getSize()`` - returns integer size in points (e.g. 10pt = 10)

* ``->getColor()`` - returns ``Imagine\Image\Palette\Color\ColorInterface`` instance, representing current font color

* ``->box($string, $angle = 0)`` - returns ``Imagine\Image\BoxInterface`` instance, representing the estimated size of the ``$string`` at the given ``$angle`` on the image
