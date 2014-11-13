Layers manipulation
===================

``ImageInterface`` provides an access for multi-layers image such as PSD files
or animated gif.
By calling the ``layers()`` method, you will get an iterable layer collection
implementing the ``LayersInterface``. As you will see, a layer implements
``ImageInterface``

Disclaimer
----------

Imagine is a fluent API to use Imagick, Gmagick or GD driver. These drivers
do not handle all multi-layers formats equally. For example :

 * PSD format should be flatten before being saved. (libraries would split it
   into different files),
 * animated gif must not be flatten otherwise the animation would be lost.
 * Tiff files should be split in multiple files or the result might be a pile
   of HD and thumbnail
 * GD does not support layers.

You have to run tests against the formats you are using and their support by
the driver you want before deploying in production.

Layers Manipulation
-------------------

Imagine ``LayersInterface`` implements PHP's ArrayAccess, IteratorAggregate and
Countable interfaces.

This provides many ways to manipulate layers.

Count Layers
++++++++++++

.. code-block:: php

    <?php
    $image = $imagine->open('image.jpg');

    echo "Image contains " . count($image->layers) . " layers";

Layers Iterations
+++++++++++++++++

.. code-block:: php

    <?php
    $image = $imagine->open('image.jpg');

    foreach ($image->layers() as $layer) {
        // ...
    }

Layers Manipulation
+++++++++++++++++++

Imagine provides an object oriented interface to manipulate layers :

.. code-block:: php

    <?php
    $image = $imagine->open('image.jpg');
    $layers = $image->layers();

    $layers->get(0)->save('layer-0.jpg');         // access a layer
    $layers->set(0, $imagine->open('image2.jpg')); // set layer at offset 0
    $layers->add($imagine->open('image3.jpg'));    // push a new layer in layers
    $layers->remove(1);                           // removes a layer at offset
    $layers->has(2);                              // test is a layer is present

You can also manipulate them like arrays :

.. code-block:: php

    <?php
    $image = $imagine->open('image.jpg');
    $layers = $image->layers();

    $layers[0]->save('layer-0.jpg');          // access a layer
    $layers[0] = $imagine->open('image2.jpg'); // set layer at offset 0
    $layers[]  = $imagine->open('image3.jpg'); // push a new layer in layers
    unset($layers[1]);                        // removes a layer at offset
    isset($layers[2]);                        // test is a layer is present

.. NOTE::
    Layers can be compared as indexed arrays. You should not use string keys.

Generate Animated gif
---------------------

Imagine provides a simple way to generate animated gif by manipulating layers :

.. code-block:: php

    <?php

    $image = $imagine->open('image.jpg');

    $image->layers()
        ->add($imagine->open('image2.jpg'))
        ->add($imagine->open('image3.jpg'))
        ->add($imagine->open('image4.jpg'))
        ->add($imagine->open('image5.jpg'));

    $image->save('animated.gif', array(
        'animated' => true,
    ));

When saving an animated gif, you are only required to use the ``animated``
option.

There are more options that can customize the output, look at the following
example :


.. code-block:: php

    <?php

    $image->save('animated.gif', array(
        'animated'       => true,
        'animated.delay' => 500, // delay in ms
        'animated.loops' => 0,   // number of loops, 0 means infinite
    ));

Animated gif frame manipulation
-------------------------------

Resizing an animated cats.gif file :

.. code-block:: php

    <?php
    $image = $imagine->open('cats.gif');

    $image->layers()->coalesce();
    foreach ($image->layers() as $frame) {
        $frame->resize(new Box(100, 100));
    }

    $image->save('resized-cats.gif', array('animated' => true));

The layers (frames) should be coalesced so that they are all in line with each other.
Otherwise you may end up with strange artifacts due to how animated GIFs *can* work.
Without going into too much detail, think of it as each frame being a patch to the previous one.
Also note that not the image, but each frame is resized. This again has to do with
how animated GIFs work. Not every frame has to be the full image.

The following example extract all frames of the cats.gif file :

.. code-block:: php

    <?php

    $i = 0;
    foreach ($imagine->open('cats.gif')->layers() as $layer) {
        $layer->save("frame-$i.png");
        $i++;
    }

This one adds some text on frames :

.. code-block:: php

    <?php

    $image = $imagine->open('cats.gif');
    $i = 0;
    foreach ($image->layers() as $layer) {
        $layer->draw()
              ->text($i, new Font('coolfont.ttf', 12, $image->palette()->color('white')), new Point(10, 10));
        $i++;
    }

    // save modified animation
    $image->save('cats-modified.gif', array('flatten' => 'false'));
