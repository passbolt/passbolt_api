Quick introduction
==================

ImagineInterface_ (``Imagine\Image\ImagineInterface``) and its implementations is the main entry point into Imagine. You may think of it as a factory for ``Imagine\Image\ImageInterface`` as it is responsible for creating and opening instances of it and also for instantiating ``Imagine\Image\FontInterface`` object.

The main piece of image processing functionality is concentrated in the ``ImageInterface`` implementations (one per driver - e.g. ``Imagick\Image``)

The main idea of Imagine is to avoid driver specific methods spill outside of this class and couple of other internal interfaces (``Draw\DrawerInterface``), so that the filters and any other image manipulations can operate on ``ImageInterface`` through its public API.

Installation
------------

The recommended way to install Imagine is through `Composer`_.
Composer is a dependency management library for PHP.

Here is an example of composer project configuration that requires imagine
version 0.4.

.. code-block:: json

    {
        "require": {
            "imagine/imagine": "~0.5.0"
        }
    }

Install the dependencies using composer.phar and use Imagine :

.. code-block:: none

    php composer.phar install
    
.. code-block:: php

    <?php
    require 'vendor/autoload.php';

    $imagine = new Imagine\Gd\Imagine();

Basic usage
-----------

Open Existing Images
++++++++++++++++++++

To open an existing image, all you need is to instantiate an image factory and invoke ``ImagineInterface::open()`` with ``$path`` to image as the  argument

.. code-block:: php

   <?php

   $imagine = new Imagine\Gd\Imagine();
   // or
   $imagine = new Imagine\Imagick\Imagine();

   $image = $imagine->open('/path/to/image.jpg');

.. TIP::
   Read more about ImagineInterface_

The ``ImagineInterface::open()`` method may throw one of the following exceptions:

* ``Imagine\Exception\InvalidArgumentException``
* ``Imagine\Exception\RuntimeException``

.. TIP::
   Read more about exceptions_

Now that you've opened an image, you can perform manipulations on it:

.. code-block:: php

   <?php

   use Imagine\Image\Box;
   use Imagine\Image\Point;

   $image->resize(new Box(15, 25))
      ->rotate(45)
      ->crop(new Point(0, 0), new Box(45, 45))
      ->save('/path/to/new/image.jpg');

.. TIP::
   Read more about ImageInterface_
   Read more about coordinates_

Resize Images
+++++++++++++

Resize an image is very easy, just pass the box size you want as argument :

.. code-block:: php

   <?php

   use Imagine\Image\Box;
   use Imagine\Image\Point;

   $image->resize(new Box(15, 25))

You can also specify the filter you want as second argument :

.. code-block:: php

   <?php

   use Imagine\Image\Box;
   use Imagine\Image\Point;
   use Imagine\Image\ImageInterface;

   // resize with lanczos filter
   $image->resize(new Box(15, 25), ImageInterface::FILTER_LANCZOS);

Available filters are ``ImageInterface::FILTER_*`` constants.

.. NOTE::
   GD only supports ``ImageInterface::RESIZE_UNDEFINED`` filter.

Create New Images
+++++++++++++++++

Imagine also lets you create new, empty images. The following example creates an empty image of width 400px and height 300px:

.. code-block:: php

   <?php

   $size  = new Imagine\Image\Box(400, 300);
   $image = $imagine->create($size);

You can optionally specify the fill color for the new image, which defaults to opaque white. The following example creates a new image with a fully-transparent black background:

.. code-block:: php

   <?php

   $palette = new Imagine\Image\Palette\RGB();
   $size  = new Imagine\Image\Box(400, 300);
   $color = $palette->color('#000', 100);
   $image = $imagine->create($size, $color);

Save Images
+++++++++++

Images are saved given a path and optionally options.

The following example opens a Jpg image and saves it as Png format :

.. code-block:: php

   <?php

   $imagine = new Imagine\Imagick\Imagine();

   $imagine->open('/path/to/image.jpg')
      ->save('/path/to/image.png');

Three options groups are currently supported : quality, resolution and flatten.

.. TIP::
   Default values are 75 for Jpeg quality, 7 for Png compression level and 72 dpi for x/y-resolution.

.. NOTE::
   GD does not support resolution options group

The following example demonstrates the basic quality settings.

.. code-block:: php

   <?php

   $imagine = new Imagine\Imagick\Imagine();

   $imagine->open('/path/to/image.jpg')
      ->save('/path/to/image.jpg', array('jpeg_quality' => 50)) // from 0 to 100
      ->save('/path/to/image.png', array('png_compression_level' => 9)); // from 0 to 9

The following example opens a Jpg image and saves it with it with 150 dpi horizontal resolution and 120 dpi vertical resolution.

.. code-block:: php

   <?php

   use Imagine\Image\ImageInterface;

   $imagine = new Imagine\Imagick\Imagine();

   $options = array(
       'resolution-units' => ImageInterface::RESOLUTION_PIXELSPERINCH,
       'resolution-x' => 150,
       'resolution-y' => 120,
       'resampling-filter' => ImageInterface::FILTER_LANCZOS,
   );

   $imagine->open('/path/to/image.jpg')->save('/path/to/image.jpg', $options);

.. NOTE::
   You **MUST** provide a unit system when setting resolution values.
   There are two available unit systems for resolution : ``ImageInterface::RESOLUTION_PIXELSPERINCH`` and ``ImageInterface::RESOLUTION_PIXELSPERCENTIMETER``.

The flatten option is used when dealing with multi-layers images (see the
`layers <layers>`_ section for information). Image are saved flatten by default,
you can avoid this by explicitly set this option to ``false`` when saving :

.. code-block:: php

   <?php

   use Imagine\Image\Box;
   use Imagine\Image\ImageInterface;
   use Imagine\Imagick\Imagine;

   $imagine = new Imagine();

   $imagine->open('/path/to/animated.gif')
           ->resize(new Box(320, 240))
           ->save('/path/to/animated-resized.gif', array('flatten' => false));

.. TIP::
   You **SHOULD** not flatten image only for animated gif and png images.

Of course, you can combine options :

.. code-block:: php

   <?php

   use Imagine\Image\ImageInterface;

   $imagine = new Imagine\Imagick\Imagine();

   $options = array(
       'resolution-units' => ImageInterface::RESOLUTION_PIXELSPERINCH,
       'resolution-x' => 300,
       'resolution-y' => 300,
       'jpeg_quality' => 100,
   );

   $imagine->open('/path/to/image.jpg')->save('/path/to/image.jpg', $options);

Advanced Examples
-----------------

Image Watermarking
++++++++++++++++++

Here is a simple way to add a watermark to an image :

.. code-block:: php

    $watermark = $imagine->open('/my/watermark.png');
    $image     = $imagine->open('/path/to/image.jpg');
    $size      = $image->getSize();
    $wSize     = $watermark->getSize();

    $bottomRight = new Imagine\Image\Point($size->getWidth() - $wSize->getWidth(), $size->getHeight() - $wSize->getHeight());

    $image->paste($watermark, $bottomRight);

An Image Collage
++++++++++++++++

Assume we were given the not-so-easy task of creating a four-by-four collage of 16 student portraits for a school yearbook.  Each photo is 30x40 px and we need four rows and columns in our collage, so the final product will be 120x160 px.

Here is how we would approach this problem with Imagine.

.. code-block:: php

   <?php

   use Imagine;

   // make an empty image (canvas) 120x160px
   $collage = $imagine->create(new Imagine\Image\Box(120, 160));

   // starting coordinates (in pixels) for inserting the first image
   $x = 0;
   $y = 0;

   foreach (glob('/path/to/people/photos/*.jpg') as $path) {
      // open photo
      $photo = $imagine->open($path);

      // paste photo at current position
      $collage->paste($photo, new Imagine\Image\Point($x, $y));

      // move position by 30px to the right
      $x += 30;

      if ($x >= 120) {
         // we reached the right border of our collage, so advance to the
         // next row and reset our column to the left.
         $y += 40;
         $x = 0;
      }

      if ($y >= 160) {
         break; // done
      }
   }

   $collage->save('/path/to/collage.jpg');

Image Reflection Filter
+++++++++++++++++++++++

.. code-block:: php

   <?php

   class ReflectionFilter implements Imagine\Filter\FilterInterface
   {
       private $imagine;

       public function __construct(Imagine\Image\ImagineInterface $imagine)
       {
           $this->imagine = $imagine;
       }

       public function apply(Imagine\Image\ImageInterface $image)
       {
           $size       = $image->getSize();
           $canvas     = new Imagine\Image\Box($size->getWidth(), $size->getHeight() * 2);
           $reflection = $image->copy()
               ->flipVertically()
               ->applyMask($this->getTransparencyMask($image->palette(), $size))
           ;

           return $this->imagine->create($canvas, $image->palette()->color('fff', 100))
               ->paste($image, new Imagine\Image\Point(0, 0))
               ->paste($reflection, new Imagine\Image\Point(0, $size->getHeight()));
       }

       private function getTransparencyMask(Imagine\Image\Palette\PaletteInterface $palette, Imagine\Image\BoxInterface $size)
       {
           $white = $palette->color('fff');
           $fill  = new Imagine\Image\Fill\Gradient\Vertical(
               $size->getHeight(),
               $white->darken(127),
               $white
           );

           return $this->imagine->create($size)
               ->fill($fill)
           ;
       }
   }

   $imagine = new Imagine\Gd\Imagine();
   $filter  = new ReflectionFilter($imagine);

   $filter->apply($imagine->open('/path/to/image/to/reflect.png'))
      ->save('/path/to/processed/image.png')
   ;

.. TIP::
   For step by step explanation of the above code `see Reflection section of Introduction to Imagine <http://speakerdeck.com/u/avalanche123/p/introduction-to-imagine?slide=31>`_

Architecture
------------

The architecture is very flexible, as the filters don't need any processing logic other than calculating the variables based on some settings and invoking the corresponding method, or sequence of methods, on the ``ImageInterface`` implementation.

The ``Transformation`` object is an example of a composite filter, representing a stack or queue of filters, that get applied to an Image upon application of the ``Transformation`` itself.

.. TIP::
   For more information about ``Transformation`` filter `see Transformation section of Introduction to Imagine <http://speakerdeck.com/u/avalanche123/p/introduction-to-imagine?slide=57>`_

.. _ImagineInterface: ../_static/API/Imagine/Image/ImagineInterface.html
.. _ImageInterface: ../_static/API/Imagine/Image/ImageInterface.html
.. _coordinates: coordinates.html
.. _exceptions: exceptions.html
.. _Composer: https://getcomposer.org/
