Migrating from CakePHP 2.0
==========================

The ImagineBehavior has been deprecated
---------------------------------------

It is still there for backward compatibility reasons but it's not going to be supported any more. Instead use `\Burzum\Imagine\Lib\ImageProcessor` directly where needed.

Before it was possible to implement your own image processing methods inside your model / table, this is gone now. But you can simply retain your code by extending the `ImageProcessor` class and adding your custom methods there.

Please pay attention that the interface of the class has changed. It is now a fluid interface:

```php
$processor = new ImageProcessor();
$processor
	->open('foo.jpg')
	->flip([/*...*/])
	->rotate([/*...*/])
	->thumbnail([/*...*/])
	->save('foo-changed.jpg');
```

If you implement your own methods make sure they'll return `$this` and that they'll have to use the protected `_image` property of the class.
