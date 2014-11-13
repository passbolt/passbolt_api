Colors
======

Imagine provides a fully-featured colors API with the Palette object :

Palette Class
+++++++++++++

Every image in Imagine is attached to a Palette. The palette handles the colors.
Imagine provides two palettes :

.. code-block:: php

   <?php

   $palette = new Imagine\Image\Palette\RGB();
   // or
   $palette = new Imagine\Image\Palette\CMYK();

When creating a new Image, the default RGB palette is used. It can be easily customized. For example the following code creates a new Image object with a white background and a CMYK palette.

.. code-block:: php

   <?php

   $palette = new Imagine\Image\Palette\CMYK();
   $imagine->create(new Imagine\Image\Box(10, 10), $palette->color('#FFFFFF'));

You can switch your palette at any moment, for example to turn a CMYK image in RGB mode :

.. code-block:: php

   <?php

   $image = $imagine->open('my-cmyk-jpg.jpg');
   $image->usePalette(new Imagine\Image\Palette\RGB())
         ->save('my-rgb-jpg.jpg');

.. NOTE::
    Switching to a palette is the same a changing the colorspace.

About colorspaces support
+++++++++++++++++++++++++

Drivers do not handle colorspace the same way.
Whereas GD only supports RGB images, Imagick supports CMYK, RGB and Grayscale
colorspaces. Gmagick only supports CMYK and RGB colorspaces.

Color Class
+++++++++++

Color is a class in Imagine, and is created through a palette with two arguments in its constructor: the RGB color code and a transparency percentage. The following examples are equivalent ways of defining a fully-transparent white color.

.. code-block:: php

   <?php

   $white = $palette->color('fff', 100);
   $white = $palette->color('ffffff', 100);
   $white = $palette->color('#fff', 100);
   $white = $palette->color('#ffffff', 100);
   $white = $palette->color(0xFFFFFF, 100);
   $white = $palette->color(array(255, 255, 255), 100);

.. NOTE::
    CMYK colors does not support alpha parameters.

After you have instantiated an RGB color, you can easily get its Red, Green, Blue and Alpha (transparency) values:

.. code-block:: php

   <?php

   var_dump(array(
      'R' => $white->getRed(),
      'G' => $white->getGreen(),
      'B' => $white->getBlue(),
      'A' => $white->getAlpha()
   ));

The same behavior is available for CMYK colors :

.. code-block:: php

   <?php

   var_dump(array(
      'C' => $white->getCyan(),
      'M' => $white->getMagenta(),
      'Y' => $white->getYellow(),
      'K' => $white->getKeyline()
   ));

Profile Class
+++++++++++++

You can apply ICC profile on any Image class with the ``profile`` method :

.. code-block:: php

   <?php

   $profile = Imagine\Image\Profile::fromPath('your-ICC-profile.icc');
   $image->profile($profile)
         ->save('my-rgb-jpg-profiled.jpg');
