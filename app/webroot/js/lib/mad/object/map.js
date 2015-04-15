/*
 * @page mad.tools Tools
 * @tag mad.tools
 * @parent index
 * 
 *  Tools style oh yeah
 */

steal(
	'mad/core/singleton.js'
).then(function () {

	/*
	 * @class mad.object.Map
	 * @inherits jQuery.Class
	 * @parent mad.tools
	 * 
	 * The Map object 
	 * 
	 * @constructor
	 * Create a new Map instance. This one will be used to map any object to another.
	 * @param {array} map The mapping to use
	 * @return {mad.object.Map}
	 */
	can.Construct('mad.object.Map', /** @static */ {

		/**
		 * @see mad.object.Map.prototype.mapObject
		 */
		'mapObject': function (object, map) {
			return map.mapObject(object);
		},

		/**
		 * @see mad.object.Map.prototype.mapObjects
		 */
		'mapObjects': function (arr, map) {
			return map.mapObjects(arr);
		}

	}, /** @prototype */ {

		/**
		 * The setting to use for the mapping
		 * @type Object
		 */
		'map': {},

		// constructor like
		'init': function (map) {
			this.map = map;
		},

		/**
		 * Map an object to another
		 * @param {Object} object The object to map
		 * @return {Object} The mapped object
		 */
		'mapObject': function (object) {
			var returnValue = {};

			getObjFieldPointer = function (object, key) {
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
			};

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
							var objectFieldToMap = getObjFieldPointer(object, keyToMap);
							// @todo what to do if the key to map does not exist
							if(objectFieldToMap != null) {
								current[mapKeyElt] = func(objectFieldToMap, this, object, returnValue);
							}
						} else {
							var objectFieldToMap = getObjFieldPointer(object, this.map[key]);
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
		 * Map an array of objects to an array of objects
		 * @param {mixed} data The data to map
		 * @return {array} The array of mapped objects
		 * @see mad.object.Map.prototype.mapObject
		 */
		'mapObjects': function (data) {
			var self = this;
			var returnValue = [];
			can.each(data, function(elt, i) { 
				returnValue[i] = self.mapObject(data[i]);
			});
			return returnValue;
		}

	});

});