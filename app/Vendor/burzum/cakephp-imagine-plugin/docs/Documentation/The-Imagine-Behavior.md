Imagine Behavior
================

The behavior interacts with the component and will process a given image file with a set of operations that should be applied to it. See ImagineBehavior::processImage().

```php
class SomeTable extends Table {
	public function initialize(array $config) {
		$this->addBehavior('Burzum/Imagine.Imagine');
	}
}
```

Image Operations
----------------

Image manipulation methods:

* **crop()**: Crops an image.
* **squareCenterCrop()**: Centers the image and crops the center as square. Useful for creating avatars for example.
* **widen()**: Makes an image more wide.
* **heighten()**: Increses the height of an image.
* **widenAndHeighten()**: Widen and heighten (stretch) an image.
* **scale()**: Scales an image.
* **flip()**: Flips an image.
* **rotate()**: Rotates an image.
* **thumbnail()**: Creates a thumbnail.
* **resize()**: Resizes an image.

Utility methods:

* **processImage()**: Takes the processing informatio and does the actual image operations.
* **getImageSize()**: Gets the size of an image.
* **operationsToString():** Turns the operations into a string format.
* **hashImageOperations():** Hashes the image operations.
* **imagineObject():** Returns the Imagine object.

Imagine instance
----------------

The plain  Imagine instance is available through the model. Get it by calling

```php
$this->imagineObject();
```

or directly through the behavior

```php
$this->Behaviors->Imagine->Imagine
```
