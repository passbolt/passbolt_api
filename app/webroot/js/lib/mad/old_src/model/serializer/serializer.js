steal(
	'can/construct'
).then(function () {

	/*
	 * @class mad.model.serializer.Serializer
	 * @inherits jQuery.Class
	 * @parent mad.core
	 */
	can.Construct('mad.model.serializer.Serializer', /** @static */ {
		'from': function (data) {
			// override this function to support the format to serialize from
		},
		'to': function (data) {
			// override this function to support the format to serialize to
		}
	}, /** @prototype */ {});
});