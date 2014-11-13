steal(
	'mad/model'
).then(function () {

	/*
	 * @class passbolt.model.ImageStorage
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The Image Storage model
	 * 
	 * @constructor
	 * Creates a category
	 * @param {array} options
	 * @return {passbolt.model.ImageStorage}
	 */
	mad.model.Model('passbolt.model.ImageStorage', /** @static */	{

	}, /** @prototype */ {

		/**
		 * Get the image path
		 * @param {passbolt.model.ImageStorage} img The target image
		 * @param {string} version (optional) The version to get
		 * @return {string} The image path
		 */
		'imagePath': function(version) {
			var versionHash = '';
			if (typeof version != 'undefined') {
				versionHash = '.' + version;
			}
			if (typeof this.id == 'undefined') {
				return '';
			} else {
				return mad.Config.read('image_storage.public_path') + '/' + this.path + this.id.replace(/\-/g, '') + versionHash + '.' + this.extension;
			}
		}

	});
});
