steal(
	'jquery/class'
).then(function () {

	/*
	 * @class mad.model.serializer.Serializer
	 * @inherits jQuery.Class
	 * @parent mad.core
	 */
	$.Class('mad.model.serializer.Serializer', /** @static */ {
		'from': function (data) {
			// override this function to support the format to serialize from
		},
		'to': function (data) {
			// override this function to support the format to serialize to
		}
	}, /** @prototype */ {});
});