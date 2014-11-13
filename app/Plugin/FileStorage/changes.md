# Changes of the FileStorage plugin

List of changes done to the plugin versions

## 0.4.0

* Change: Removed deprecated ImageStorage::createVersions()
* Change: Replaced the static calls of CakeEventManager with getEventManager() in the models
* Feature: Configurable fallback images through Configure::write('Media.fallbackImages.<model>.<version>), see ImageHelper::fallbackImage()
* Feature: Added AbstractStorageEventListener that can be used as base for all event listeners
* Feature: Added S3StorageListener, stores files to S3, no image processing done in this listener
* Misc: Fixed a bunch of coding standard issues
* Misc: Updated documentation

## 0.3.3

* Updating the Gaufrette Vendor lib
* Updating readme.md
* Coding standard fixes
* Fixes https://github.com/burzum/FileStorage/issues/35
* Fix: 2.4 and php5 compatibility, fixed strict errors for model callbacks

## 0.3.2

* Fix: Removed model FileStorage::$createVersions property, instead of creating no versions no file at all was saved. As replacement for FileStorage::$createVersions the LocalImageStorageListener won't create any revisions if it can find any configuration for the given model. This caused a notice before and further issues.
* Fix: The event ImageStorage.beforeSave was not triggered
* Fix: StorageManager::config($configName) now returns the correct config instead of always active
* Fix: ImageStorage::afterSave() does not call the parent anymore to avoid that the regular file storage event listener saves the image already, this happened when an image and a normal file were used within the same form
* Fix: Duplicate error message when UploadValidatorBehavior::validateAllowedMimeTypes() fails
* Change: LocalImageProcessingListener is deprecated, used ImageProcessingListener
* Change: The / that was prepended in the ImageHelper::imageUrl has been move to the LocalImageProcessingListener because the / won't be used by all, mostly external, adapters
* Change: LocalImageProcessingListener::_tmpFile() is throwing an exception now instead of returning false
* Feature: Adding a new ImageProcessingListener that works with Amazon S3 and Local adapters, it can be pretty simple enhanced, let me know or do a PR with your changes for another adapter
* Feature: ImageProcessingListener can be configured to preserve the original filename instead of using an uuid for the filename, see it's constructor
* Feature: Added jpeg to the allowed extensions besides jpg of the ImageStorage model 


## Change log before 0.3.2

Not available.
