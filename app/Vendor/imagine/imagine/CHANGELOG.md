# CHANGELOG

### 0.6.3 (2015-09-19)
  * Fix wrong array_merge when calling Transformation::getFilters without filters
  * Add export-ignore git attribute (@Benoth)
  * Fix docblocks (@Sm0ke0ut and @norkunas)
  * Fix animated gif loop length options (@jygaulier)
  * Multiple tweaks for the repository and travis builds (@localheinz, @vrkansagara and @dunzun)
  * Fix metadata extraction from streams (@armatronic)
  * Fix autorotation (@tarleb)
  * Load exifmetadata reader whenever possible
  * Add metadata getter

### 0.6.2 (2014-11-11)
  * Stripping image containing an invalid ICC profile fails
  * MetadataBag now implements \Countable
  * Fix wrong array_merge in MetadataBag giving invalid results with HTTP resources (@javaguirre)
  * Fix Imagick merge strategy (@GrahamCampbell)
  * Fixed various alpha issues (@RadekDvorak)
  * Fix Image cloning on HHVM (@RdeWilde)
  * Fix exception on invalid file using GD driver (@vlakoff).
  * Fix ImageInterface::getSize on animated GIFs (@sokac)

### 0.6.1 (2014-06-16)
  * Fix invalid namespace usage (#336 @csanquer).

### 0.6.0 (2014-06-13)

  * BC break: Colors are now provided through the PaletteInterface. Any call
    to previous Imagine\Image\Color constructor must be removed and use the
    palette provided by Imagine\Image\ImageInterface::getPalette to create
    colors.
  * BC break : Animated GIF default delay is no longer 800ms but null. This 
    avoids resettings a delay on animated image.
  * Add support for ICC profiles
  * Add support for CMYK and grayscale colorspace images.
  * Add filter argument to ImageInterface::thumbnail method.
  * Add priority to filters (@Richtermeister).
  * Add blur effect (@Nokrosis).
  * Rename "quality" option to "jpeg_quality" and apply it only to JPEG files (@vlakoff).
  * Add "png_compression_level" option (@vlakoff).
  * Rename "filters" option to "png_compression_filter" (@vlakoff).
  * Deprecate `quality` and `filters` ManipulatorInterface::save options, use
    `jpeg_quality`, `png_compression_level` and `png_compression_filter` instead.
  * Add support for alpha blending in GD drawer (@salem).
  * Add width parameter to Drawer::text (@salemgolemugoo).
  * Add NotSupportedException when a driver does not support an operation (@rouffj).
  * Add support for metadata.
  * Fix #158: GD alpha detection + Color::isOpaque are broken.
  * Fix color extraction for non-RGB palettes.

### 0.5.0 (2013-07-10)

  * Add `Layers::coalesce`.
  * Add filter option to `ImageInterface::resize`.
  * Add sharpen effect.
  * Add interlace support.
  * `LayersInterface` now extends `ArrayAccess`, gives support for animated gifs.
  * Remove Imagick and Gmagick flatten after composite.
  * Fix pixel opacity reading in `Gmagick::histogram`.
  * Deprecate pear channel installation.
  * Deprecate phar installation.

### 0.4.1 (2012-12-13)

  * Lazy-load GD layers.

### 0.4.0 (2012-12-10)

  * Add support for image Layers.
  * Add Colorize effect.
  * Add documentation for the Grayscale effect.
  * Port RelativeResize filter from JmikolaImagineBundle.

### 0.3.1 (2012-11-12)

  * Add Grayscale effect.
  * `Drawer::text` position fix.

### 0.3.0 (2012-07-28)

  * Add configurable border thickness to drawer interface and implementations.
  * Add `ImageInterface`::strip.
  * Add Canvas filter.
  * Add resolution option on image saving.
  * Add Grayscale filter.
  * Add sami API documentation.
  * Add compression quality to Gmagick.
  * Add effects API.
  * Add method to get pixel at point in Gmagick.
  * Ensure valid background color in rotations.
  * Fill lines with color to prevent semi-transparency issues.
  * Use `Imagick::resizeImage` instead of `Imagick::thumbnailImage` for resizing.
  * Fix PNG transparency on save ; do not flatten if not necessary.

### 0.2.8 (2011-11-29)

  * Add support for Travis CI.

### 0.2.7 (2011-11-17)

  * Use composer for autoloading.

### 0.2.6 (2011-11-09)

  * Documentation enhancements.

### 0.2.5 (2011-10-29)

  * Add PEAR support.
  * Documentation enhancements.

### 0.2.4 (2011-10-17)

  * Add imagine.phar, phar and rake tasks.
  * Add `ImagineInterface::read` to read from a stream resource.
  * Documentation enhancements.
  * Fix gifs transparency issues.

### 0.2.3 (2011-10-16)

  * Documentation enhancements.

### 0.2.2 (2011-10-16)

  * Documentation enhancements.

### 0.2.1 (2011-10-15)

  * Add `PointInterface::move`.
  * `BoxInterface::scale` can accept floats.
  * Set antialias mode for GD images.
  * Fix png compression.

### 0.2.0 (2011-10-06)

  * Add `Imagine\Fill\Gradient\Linear::getStart`/`getEnd`.
  * Add `Imagine\Image\Color::isOpaque`.
  * Add Gmagick transparency exceptions.
  * Add support for transparency for gif export.
  * Add widen/heighten methods for easy scaling to target width/height.
  * Add functionals tests to unit test thumbnails creation.
  * Add the ability to use hexadecimal notation for `Imagine\Image\Color` construction.
  * Implement fast linear gradient for Imagick.
  * Remove lengthy image histogram comparisons.
  * Extract `ManipulatorInterface` from `ImageInterface`.
  * Switch methods to final.
  * New method `ImageInterface::getColorAt`.
  * Introduce `ImagineAware` abstract filter class.

### 0.1.5 (2011-05-18)

  * Fix bug in GD rotate.

### 0.1.4 (2011-03-21)

  * Add environment check to gracefuly skip test.

### 0.1.3 (2011-03-21)

  * Improve api docs.
  * Extract `FontInterface`.

### 0.1.2 (2011-03-21)

  * Add check for GD.

### 0.1.1 (2011-03-21)

  * Add rounding and fixed thumbnail logic.

### 0.1.0 (2011-03-14)

  * First tagged version.
