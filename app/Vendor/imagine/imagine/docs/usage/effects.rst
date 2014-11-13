Image effects
=============

Imagine also provides a fully-featured effects API.
To use the api, you need to get an effect instance from you current image
instance, using ``ImageInterface::effects()`` method.

Example
-------

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $image->effects()
        ->negative()
        ->gamma(1.3);

    $image->save('negative-portrait.png');

The above example would open a "portrait.jpeg" image, invert the colors, then
corrects the gamma with a parameter of 1.3 then saves it to a new file
"negative-portrait.png".

.. NOTE::
    As you can notice, all effects are chainable.

Effects API
-----------

The current Effects API currently supports these effects :

Negative
++++++++

The negative effect inverts the color of an image :

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $image->effects()
        ->negative();

    $image->save('negative-portrait.png');

Gamma correction
++++++++++++++++

Apply a gamma correction. It takes one float argument, the correction parameter.

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $image->effects()
        ->gamma(0.7);

    $image->save('negative-portrait.png');

Grayscale
+++++++++

Create a grayscale version of the image.

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $image->effects()
        ->grayscale();

    $image->save('grayscale-portrait.png');

Colorize
++++++++

Colorize the image. It takes one ``Imagine\Image\Palette\Color\ColorInterface`` argument, which represents the color applied on top of the image.

This feature only works with the Gd and Imagick drivers.

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $pink = $image->palette()->color('#FF00D0');

    $image->effects()
        ->colorize($pink);

    $image->save('pink-portrait.png');

Blur
++++

Blur the image. It takes a string argument, which represent the sigma used for 
Imagick and Gmagick functions (defaults to 1).

.. code-block:: php

    <?php

    $image = $imagine->open('portrait.jpeg');

    $image->effects()
        ->blur(3);

    $image->save('blurred-portrait.png');

.. NOTE::
    Sigma value has no effect on GD driver. Only GD's IMG_FILTER_GAUSSIAN_BLUR filter is applied instead.
