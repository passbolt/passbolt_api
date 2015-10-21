import 'can/construct/construct';
import 'mad/model/serializer/serializer';

mad.model.serializer = mad.model.serializer || {};

/**
 * @inherits jQuery.Class
 * @parent mad.core
 */
var CakeSerializer = mad.model.serializer.CakeSerializer = can.Construct.extend('mad.model.serializer.CakeSerializer', /** @static */ {
    from: function (data, Class) {
        var returnValue = {};
        returnValue = $.extend(true, {}, data, data[Class.shortName]);
        delete returnValue[Class.shortName];
        return returnValue;
    },
    to: function (data, Class) {
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

export default CakeSerializer;