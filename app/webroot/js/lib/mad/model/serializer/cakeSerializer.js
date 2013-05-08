steal(
	'can/construct',
	'mad/model/serializer/serializer.js'
).then(function () {

	/*
	 * @class mad.model.serializer.Serializer
	 * @inherits jQuery.Class
	 * @parent mad.core
	 */
	can.Construct('mad.model.serializer.CakeSerializer', /** @static */ {
		'from': function (data, Class) {
			var returnValue = {};
			returnValue = $.extend(true, {}, data, data[Class.shortName]);
			delete returnValue[Class.shortName];
			return returnValue;
		},
		'to': function (data, Class) {
			var returnValue = {};
			returnValue[Class.shortName] = {};
			for (var name in data) {
				if (Class.isModelAttribute(name)) {
					returnValue[name] = data[name];
				} else {
					returnValue[Class.shortName][name] = data[name];
				}
			}
			return returnValue;
		}
	}, /** @prototype */ {});
});