Caching and Storage
===================

This plugin *does not* take care of how you store the images or how you cache them but it will offer you some helping methods for caching images based on a hash or a unique string.

This is a design decision that was made because everyone likes to implement the file storage a little different. So it is up to you how you store the generated images. If you're looking for a ready to use solution see the next section.

Storing Images with FileStorage and automated processing with Imagine
---------------------------------------------------------------------

See the [FileStorage](https://github.com/burzum/cakephp-file-storage) plugin for storing images and files in your application. FileStorage features automated image processing after upload and uses Imagine for that. Check it out, it works very well and is in use by a few bigger well known companies.
