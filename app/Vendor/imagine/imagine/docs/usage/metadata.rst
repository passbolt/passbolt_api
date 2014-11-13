Metadata
========

Imagine 0.6 comes with an abstraction to read Image metadata.

Access image metadata
---------------------

Metadata are read with a ``Imagine\Image\Metadata\MetadataReaderInterface`` and
accessible through ``Imagine\Image\ImageInterface::metadata`` method that returns
a ``Imagine\Image\Metadata\MetadataBag`` object:

.. code-block:: php

    <?php

    use Imagine\Image\Metadata\ExifMetadataReader;

    $image = $imagine->open('/path/to/image.jpg');
    $metadata = $image->metadata();

    // prints '/path/to/image.jpg'
    print($metadata['filename']);


Change the metadata reader
--------------------------

Imagine comes bundled with two metadata readers: ``Imagine\Image\Metadata\DefaultMetadataReader``
and ``Imagine\Image\Metadata\ExifMetadataReader``.

Imagine uses the default metadata reader by default. You can easily switch to
the one you want using ``Imagine\Image\ImagineInterface::setMetadataReader``
method.

.. code-block:: php

    <?php

    use Imagine\Image\Metadata\ExifMetadataReader;

    $imagine->setMetadataReader(new ExifMetadataReader());

Default Metadata Reader
+++++++++++++++++++++++

The default metadata reader is a basic reader that stores original information
about the resource.

.. code-block:: php

    <?php

    use Imagine\Image\Metadata\DefaultMetadataReader;

    $image = $imagine
        ->setMetadataReader(new DefaultMetadataReader())
        ->open('chenille.jpg');
    $metadata = $image->metadata();

    var_dump($metadata->toArray());

The previous code might produce such output:

.. code-block:: none

    array(2) {
      'filepath' =>
      string(60) "/Users/romainneutron/Documents/workspace/Imagine/chenille.jpg"
      'uri' =>
      string(12) "chenille.jpg"
    }

Exif Metadata Reader
++++++++++++++++++++

Exif Metadata Reader gives the same base information as the default metadata reader
and adds exif data provided by the Exif extension.

.. note::

    Using the exif metadata reader adds a significant overhead to image processing.

.. code-block:: php

    <?php

    use Imagine\Image\Metadata\ExifMetadataReader;

    $image = $imagine
        ->setMetadataReader(new ExifMetadataReader())
        ->open('chenille.jpg');
    $metadata = $image->metadata();

    var_dump($metadata->toArray());

The previous code should produce this output:

.. code-block:: none

    array(37) {
      'filepath' =>
      string(60) "/Users/romainneutron/Documents/workspace/Imagine/chenille.jpg"
      'uri' =>
      string(12) "chenille.jpg"
      'exif.ExposureTime' =>
      string(5) "1/120"
      'exif.FNumber' =>
      string(4) "11/5"
      'exif.ExposureProgram' =>
      int(2)
      'exif.ISOSpeedRatings' =>
      int(40)
      'exif.ExifVersion' =>
      string(4) "0221"
      'exif.DateTimeOriginal' =>
      string(19) "2014:04:06 16:11:59"
      'exif.DateTimeDigitized' =>
      string(19) "2014:04:06 16:11:59"
      'exif.ComponentsConfiguration' =>
      string(4) "\000"
      'exif.ShutterSpeedValue' =>
      string(9) "9488/1373"
      'exif.ApertureValue' =>
      string(9) "7801/3429"
      'exif.BrightnessValue' =>
      string(8) "4457/710"
      'exif.MeteringMode' =>
      int(3)
      'exif.Flash' =>
      int(16)
      'exif.FocalLength' =>
      string(6) "103/25"
      'exif.ColorSpace' =>
      int(1)
      'exif.ExifImageWidth' =>
      int(2048)
      'exif.ExifImageLength' =>
      int(1536)
      'exif.SensingMethod' =>
      int(2)
      'exif.ExposureMode' =>
      int(0)
      'exif.WhiteBalance' =>
      int(0)
      'exif.FocalLengthIn35mmFilm' =>
      int(30)
      'exif.SceneCaptureType' =>
      int(0)
      'exif.UndefinedTag:0xA433' =>
      string(5) "Apple"
      'exif.UndefinedTag:0xA434' =>
      string(34) "iPhone 5s back camera 4.12mm f/2.2"
      'ifd0.Make' =>
      string(5) "Apple"
      'ifd0.Model' =>
      string(9) "iPhone 5s"
      'ifd0.XResolution' =>
      string(4) "72/1"
      'ifd0.YResolution' =>
      string(4) "72/1"
      'ifd0.ResolutionUnit' =>
      int(2)
      'ifd0.Software' =>
      string(5) "7.0.4"
      'ifd0.DateTime' =>
      string(19) "2014:04:06 16:11:59"
      'ifd0.YCbCrPositioning' =>
      int(1)
      'ifd0.Exif_IFD_Pointer' =>
      int(192)
      'ifd0.GPS_IFD_Pointer' =>
      int(1486)
    }

Create your own metadata reader
-------------------------------

Any metadata reader must implement ``Imagine\Image\Metadata\MetadataReaderInterface``.
However it's easier to extend ``Imagine\Image\Metadata\AbstractMetadataReader``
to avoid missing things and focus on the purpose of the reader.

Here's an example of a metadata reader that retrieves posix access information from
a file:

.. code-block:: php

    <?php

    use Imagine\Image\Metadata\AbstractMetadataReader;

    class PosixMetadataReader extends AbstractMetadataReader
    {
        /**
         * {@inheritdoc}
         */
        protected function extractFromFile($file)
        {
            // if file is not local, forget it
            if (!stream_is_local($file)) {
                return array();
            }

            return array(
                'access' => posix_access($file),
            );
        }

        /**
         * {@inheritdoc}
         */
        protected function extractFromData($data);
        {
            // posix informations about raw data in non-sense
            return array();
        }

        /**
         * {@inheritdoc}
         */
        protected function extractFromStream($resource)
        {
            if (!stream_is_local($file)) {
                return array();
            }

            if (false !== $data = @stream_get_meta_data($resource)) {
                return array(
                    'access' => posix_access($data['uri']),
                );
            }

            return array();
        }
    }

