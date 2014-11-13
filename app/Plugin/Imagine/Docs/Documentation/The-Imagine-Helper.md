Imagine Helper
==============

The helper will generate image urls with named params to get thumbnails or whatever else operation is wanted and a hashes the url.

The hash can be checked using the Imagine Component to avoid that people try to bring your page down by incrementing the size of a requested thumbnail to generate thousands of images on your server.

```php
$url = $this->Imagine->url(
	array(
		'controller' => 'images',
		'action' => 'display',
		1
	),
	array(
		'thumbnail' => array(
			'width' => 200,
			'height' => 150
		)
	)
);

echo $this->Html->image($url);
```