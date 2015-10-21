import 'can/construct/construct';

mad.model.serializer = mad.model.serializer || {};

/**
 * @inherits jQuery.Class
 * @parent mad.core
 */
var Serializer = mad.model.serializer.Serializer = can.Construct.extend('mad.model.serializer.Serializer', /** @static */ {
    from: function (data) {
        // override this function to support the format to serialize from
    },
    to: function (data) {
        // override this function to support the format to serialize to
    }
}, /** @prototype */ {});

export default Serializer;
