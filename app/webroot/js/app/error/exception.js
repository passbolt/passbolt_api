// Initialize the error namespaces.
passbolt.error = passbolt.error || {};
passbolt.error.WRONG_PARAMETER = "Wrong parameter [%0]";
passbolt.error.MISSING_OPTION = "The option [%0] should be defined";
passbolt.error.ELEMENT_NOT_FOUND = "The element [%0] could not be found";

var PassboltException = passbolt.Exception = function() {
};

PassboltException.get = function(exception_message) {
	var reps = Array.prototype.slice.call(arguments, 1);
	var message = exception_message.replace(/%(\d+)/g, function(s, key) {
		return reps[key] || s;
	});
	return new Error(message)
};
