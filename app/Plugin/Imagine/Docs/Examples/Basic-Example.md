Basic Example
=============

Attach the behavior to the model:

```php
Image extends AppModel {
	public $actsAs = array(
		'Imagine.Imagine'
	);
}
```

Now you can start manipulating images:

```php
$imageOperations = array(
	'flip' => array(
		'direction' => 'vertically'
	),
	'crop' => array(
		'height' => 100,
		'width' => 100
	),
);

$this->Image->processImage(
	APP . 'files' . DS . 'image.jpg',
	APP . 'files' . DS . 'modifiedImage.jpg',
	array(),
	$imageOperations
);
```