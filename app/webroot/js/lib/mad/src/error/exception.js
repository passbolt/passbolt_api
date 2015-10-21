import mad from 'mad/util/util';

// Initialize the error namespaces.
mad.error = mad.error || {};
mad.error.WRONG_PARAMETER = "Wrong parameter [%0]";
mad.error.MISSING_OPTION = "The option [%0] should be defined";
mad.error.ELEMENT_NOT_FOUND = "The element [%0] could not be found";
mad.error.MISSING_CONFIG = "The config [%0] has to be defined";

var MadException = mad.Exception = function() {
};

MadException.get = function(exception_message) {
    var reps = Array.prototype.slice.call(arguments, 1);
    var message = exception_message.replace(/%(\d+)/g, function(s, key) {
        return reps[key] || s;
    });
    return new Error(message)
};
