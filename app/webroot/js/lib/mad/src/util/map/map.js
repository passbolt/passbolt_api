import 'mad/util/util';

/**
 * @parent Mad.core_api
 * @inherits mad.Construct
 *
 * The aim of the object Map is to help developers to transform an object into another, by mapping
 * its fields following another structure.
 */
var Map = mad.Map = can.Construct.extend('mad.Map', /** @static */ {

	/**
	 * @deprecated {0.0.2} Please call the function mapObject directly on the map object.
	 */
	mapObject: function (object, map) {
		console.warn('Please call the function mapObject directly on the map object.');
		return map.mapObject(object);
	},

	/**
	 * @deprecated {0.0.2} Please call the function mapObject directly on the map object.
	 */
	mapObjects: function (arr, map) {
		console.warn('Please call the function mapObject directly on the map object.');
		return map.mapObjects(arr);
	}

}, /** @prototype */ {

	/**
	 * The map setting to use for the mapping.
	 */
	map: {},

	/**
	 * Constructor.
	 *
	 * @param {object} map The map setting to use for the mapping.
	 * @return {mad.object.Map}
	 *
	 * @body
	 * TBD some example of map setting.
	 */
	init: function (map) {
		this.map = map;
	},

	/*
	 * Retrieve the value of a nested key (level1.level2.level3...) into an object.
	 * @param {object} object The object to look into
	 * @param {string} key The key to look for
	 * @return {mixed}
	 */
	_getObjFieldPointer: function (object, key) {
		var returnValue = object,
			split = key.split('.');

		for (var i = 0; i < split.length; i++) {
			// Failed to find the subkey.
			if (returnValue[split[i]] === undefined) {
				return null;
			}
			returnValue = returnValue[split[i]];
		}
		return returnValue;
	},

	/**
	 * Map an object into another.
	 *
	 * @param {Object} object The object to map
	 * @return {Object} The mapped object
	 */
	mapObject: function (object) {
		var returnValue = {};

		for (var key in this.map) {
			var mapKeyElts = key.split('.'),
				// the map keys (targetKey || targetKeyLvl1.targetKeyLvl2)
				current = returnValue,
				// the current position in the final object
				position = 0; // position of the cursors in the mapKeyElts

			// foreach mapKeyElts we add add a level in the final object
			// at the leaf we insert the value
			for (var i in mapKeyElts) {
				var mapKeyElt = mapKeyElts[i];

				// if the leaf is reached
				if (position == mapKeyElts.length - 1) {

					// if a transformation func is given
					if (typeof this.map[key] == 'object') {
						var func = this.map[key].func;
						var keyToMap = this.map[key].key;
						var objectFieldToMap = this._getObjFieldPointer(object, keyToMap);
						// @todo what to do if the key to map does not exist
						if(objectFieldToMap != null) {
							current[mapKeyElt] = func(objectFieldToMap, this, object, returnValue);
						}
					} else {
						var objectFieldToMap = this._getObjFieldPointer(object, this.map[key]);
						// @todo what to do if the key to map does not exist
						if(objectFieldToMap != null) {
							current[mapKeyElt] = objectFieldToMap;
						}
					}

				}
				// else we move the cursor in the mapKeyElts
				else {
					if(typeof current[mapKeyElt] == 'undefined') current[mapKeyElt] = [];
					current = current[mapKeyElt];
				}

				position++;
			}
		}

		return returnValue;
	},

	/**
	 * Map an array of objects into an array of transformed objects.
	 *
	 * @param {mixed} data The array of objects to map.
	 * @return {array} The array of mapped objects.
	 */
	mapObjects: function (data) {
		var self = this;
		var returnValue = [];
		can.each(data, function(elt, i) {
			returnValue[i] = self.mapObject(data[i]);
		});
		return returnValue;
	}

});

export default Map;
