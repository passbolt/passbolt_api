Handling exceptions
===================

Imagine is good with exceptions, in fact, it will throw a lot of them for every possible thing that goes wrong. There are no methods that return ``false`` on failure, its all exception based.

Exception interface
-------------------

Every exception class in Imagine implements Exception_ (``Imagine\Exception\Exception``) interface, making it possible to catch all Imagine exceptions without catching anything not Imagine specific.

.. code-block:: php

    <?php

    try {
        $imagine = new Imagine\Gd\Imagine();

        $imagine->open('/path/to/image.jpg')
            ->thumbnail(new Imagine\Image\Box(50, 50))
            ->save('/path/to/image/thumbnail.png');
    } catch (Imagine\Exception\Exception $e) {
        // handle the exception
    }

This is too generic however and might not work for everyone.

Exception classes
-----------------

In Imagine, each exception class is extending one of the SPL exception classes, so even if you simply handle SPL exception, Imagine should fit right in. For example ``Imagine\Exception\InvalidArgumentException`` class extends ``InvalidArgumentException``, letting you catch it as an SPL exception or by catching its instance specifically

.. NOTE::
    This technique came from Zend Framework 2

.. _Exception: ../_static/API/Imagine/Exception.html