import 'mad/model/model';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The Image Storage model
 *
 * @constructor
 * Creates an image storage
 * @param {array} options
 * @return {passbolt.model.ImageStorage}
 */
var ImageStorage = passbolt.model.ImageStorage = mad.Model.extend('passbolt.model.ImageStorage', /** @static */ {

}, /** @prototype */ {

	/**
	 * Get the image path
	 * @param {passbolt.model.ImageStorage} img The target image
	 * @param {string} version (optional) The version to get
	 * @return {string} The image path
	 */
	imagePath: function(version) {
		if (typeof this.url == 'undefined') {
			return '';
		}
		if (typeof this.url[version] == 'undefined') {
			return '';
		}
		else {
			return this.url[version];
		}
	}
});

export default ImageStorage;