.. Imagine documentation master file, created by
   sphinx-quickstart on Wed Apr  6 00:20:22 2011.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to Imagine's documentation!
===================================

Imagine is a OOP library for image manipulation built in PHP 5.3 using the latest best practices and thoughtful design that should allow for decoupled and unit-testable code.

.. code-block:: php

    <?php

    $imagine = new Imagine\Gd\Imagine();
    // or
    $imagine = new Imagine\Imagick\Imagine();
    // or
    $imagine = new Imagine\Gmagick\Imagine();

    $size    = new Imagine\Image\Box(40, 40);

    $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
    // or
    $mode    = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

    $imagine->open('/path/to/large_image.jpg')
        ->thumbnail($size, $mode)
        ->save('/path/to/thumbnail.png')
    ;


Enjoy!


Contribute:
-----------

Your contributions are more than welcome !

Start by `forking Imagine repository <https://github.com/avalanche123/Imagine>`_, write your feature, fix bugs, and send a `pull request <https://help.github.com/articles/using-pull-requests>`_.
If you modify Imagine API, please update the API documentation by running at the root of Imagine project:

.. code-block:: bash

    curl -s http://getcomposer.org/installer | php
    php composer.phar install --dev
    bin/sami.php update docs/sami_configuration.php -v

and commit the updated files in the *docs/API/* folder.

If you're a beginner, you will find some guidelines about code contributions at `Symfony <http://symfony.com/doc/current/contributing/code/patches.html>`_


Ask a question:
---------------

We're on IRC: ``#php-imagine`` on Freenode


Usage:
---------

.. toctree::
   :maxdepth: 3

   usage/introduction
   usage/coordinates
   usage/drawing
   usage/colors
   usage/effects
   usage/layers
   usage/filters
   usage/exceptions

Api docs:
---------

Find them in the `API browser <_static/API>`_


A couple of words in defense
----------------------------

After reading the documentation and working with the library for a little while, you might be wondering "Why didn't he keep width and height as simple integer parameters in every method that needed those?" or "Why is x and y coordinates are an object called Point?". These are valid questions and concerns, so let me try to explain why:

**Type-hints and validation** - instead of checking for the validity of width and height (e.g. positive integers, greater than zero) or x, y (e.g. non-negative integers), I decided to move that check into constructor of ``Box`` and ``Point`` accordingly. That means, that if something passes the type-hint - a valid implementations of ``BoxInterface`` or ``PointInterface``, it is already valid.

**Utility methods** - a lot of functionality, like "determine if a point is inside a given box" or "can this box fit the one we're trying to paste into it" is also to be shared in many places. The fact that these primitives are objects, lets me extract all of that duplication.

**Value objects** - as you've noticed neither ``BoxInterface`` nor ``PointInterface`` along with their implementations define any setter. That means the state of those objects is immutable, so there aren't side-effects to happen and the fact that they're passed by reference, will not affect their values.

**It's OOP man**, come on - nothing to add here, really.

Indices and tables
==================

* :ref:`genindex`
* :ref:`search`


