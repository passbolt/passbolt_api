Replacing Files
===============

A common task is to replace on existing image with a new image.

Assuming we have a model called `Document` that is associated by a`hasOne` association with the `ImageStorage` model. The associatons alias is `Image`. Your form should look like this:

```php
echo $this->Form->file('Image.file');
echo $this->Form->error('Image.file');

if (isset($document) && !empty($document['Image']['id'])) {
	echo $this->Image->display($document['Image']);
	echo $this->Form->input('Image.old_file_id', array(
		'type' => 'hidden',
		'value' => $document['Document']['id'],
	));
}
```

The the trick here is the `old_file_id`. The `FileStorage` model, which `ImageStorage` extends, is checking for that field by calling `FileStorage::deleteOldFileOnSave()` in `FileStorage::afterSave()`.

So all you have to do to replace an image is to pass the `old_file_id` along with your new file data.

Just make sure that nobody can tamper your forms with unwanted data! If somebody can do that they can pass any id to delete *any* file! It is recommended to use the [Security component](http://book.cakephp.org/2.0/en/core-libraries/components/security-component.html) of the framework to avoid that.
