Basic Example
=============

Add the behavior to the table:

```php
class SomeTable extends Table {
	public function initialize(array $config) {
		$this->addBehavior('Burzum/Imagine.Imagine');
	}
}
```

Now you can start manipulating images.

Flip an image vertical and crop it to 100x100px:

```php
$imageOperations = array(
	'flip' => [
		'direction' => 'vertically'
	],
	'crop' => [
		'height' => 100,
		'width' => 100
	],
);

$this->Image->processImage(
	APP . 'files' . DS . 'image.jpg',
	APP . 'files' . DS . 'modifiedImage.jpg',
	[],
	$imageOperations
);
```

Create a thumbnail with a max height of 600px and a max width of 200px:

```php
$imageOperations = [
	'thumbnail' => [
		'height' => 600,
		'width' => 200
	],
];

$this->Image->processImage(
	APP . 'files' . DS . 'image.jpg',
	APP . 'files' . DS . 'modifiedImage.jpg',
	[],
	$imageOperations
);
```


