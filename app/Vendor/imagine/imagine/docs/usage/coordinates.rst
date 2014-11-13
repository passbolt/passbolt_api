Imagine's coordinates system
============================

The coordinate system use by Imagine is very similar to Cartesian Coordinate System, with some exceptions:

* Coordinate system starts at x,y (0,0), which is the top left corner and extends to right and bottom accordingly
* There are no negative coordinates, a point must always be bound to the box its located at, hence 0,0 and greater
* Coordinates of the point are relative its parent bounding box

Classes
-------

The whole coordinate system is represented in a handful of classes, but most importantly - its interfaces:

* ``Imagine\Image\PointInterface`` - represents a single point in a bounding box

* ``Imagine\Image\BoxInterface`` - represents dimensions (width, height)

PointInterface
--------------

Every coordinate contains the following methods:

* ``->getX()`` - returns horizontal position of the coordinate

* ``->getY()`` - returns vertical position of a coordinate

* ``->in(BoxInterface $box)`` - returns ``true`` if current coordinate appears to be inside of a given bounding ``$box``

* ``->__toString()`` - returns string representation of the current ``PointInterface``, e.g. ``(0, 0)``

Center coordinate
+++++++++++++++++

It is very well known use case when a coordinate is supposed to represent a center of something.

As part of showing off OO approach to image processing, I added a simple implementation of the core ``Imagine\Image\PointInterface``, which can be found at ``Imagine\Image\Point\Center``. The way it works is simple, it expects and instance of ``Imagine\Image\BoxInterface`` in its constructor and calculates the center position based on that.

.. code-block:: php

    <?php

    $size = new Imagine\Image\Box(50, 50);
    
    $center = new Imagine\Image\Point\Center($size);
    
    var_dump(array(
        'x' => $center->getX(),
        'y' => $center->getY(),
    ));
    
    // would output position of (x,y) 25,25

BoxInterface
-------------

Every box or image or shape has a size, size has the following methods:

* ``->getWidth()`` - returns integer width

* ``->getHeight()`` - returns integer height

* ``->scale($ratio)`` - returns a new ``BoxInterface`` instance with each side multiplied by ``$ratio``

* ``->increase($size)`` - returns a new ``BoxInterface``, with given ``$size`` added to each side

* ``->contains(BoxInterface $box, PointInterface $start = null)`` - checks that the given ``$box`` is contained inside the current ``BoxInterface`` at ``$start`` position. If no ``$start`` position is given, its assumed to be (0,0)

* ``->square()`` - returns integer square of current ``BoxInterface``, useful for determining total number of pixels in a box for example

* ``->__toString()`` - returns string representation of the current ``BoxInterface``, e.g. ``100x100 px``

* ``->widen($width)`` - resizes box to given width, constraining proportions and returns the new box

* ``->heighten($height)`` - resizes box to given height, constraining proportions and returns the new box
