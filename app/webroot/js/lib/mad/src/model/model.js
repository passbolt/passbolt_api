import mad from 'mad/util/util';
// Plugin which will help to take care of the association between the models.
// Will be deprecated after canJs 3.0.
// see: http://canjs.com/docs/can.Map.attributes.html
import 'can/map/attributes/attributes';
import 'mad/model/list';
import 'mad/model/serializer/cake_serializer';

/**
 * The package that will contain all codes relative to Model.
 */
mad.model = mad.model || {};

/**
 * @parent Mad.core_api
 * @inherits mad.Control
 *
 * A specialisation of the can.Model class to manage specific behaviors of the mad framework.
 *
 * @parent Mad.core_api
 * @constructor mad.Model
 * @inherits can.Model
 */
var Model = mad.Model = can.Model.extend('mad.Model', /** @static */ {

    /**
    * Force the storing of this model's instances.
    * @protected
    */
    forceStore: false,

    /**
    * Store all the instance of this model in the local store.
    * @protected
    */
    madStore: new can.Model.List(),

    /**
     * The model validation rules.
     * @protected
     */
    validationRules: {},

    /**
     * The model attributes validation rules defined on the server.
     * @protected
     */
    serverValidationRules: {},

    /**
     * Check the server validation rules.
     * @protected
     */
    checkServerRules: false,

    ///**
    //* Get attribute description of this model
    //* @param {string} name The name of the attribute
    //* @return {mad.model.Attribute}
    //*/
    //getAttribute: function (name) {
    //	var returnValue = null,
    //		modelName = this.attributes[name].substr(0, this.attributes[name].lastIndexOf('.')),
    //		model = $.String.getObject(modelName);
    //	returnValue = new mad.model.Attribute({
    //		'name': name,
    //		'multiple': this.isMultipleAttribute(name),
    //		'modelReference': model
    //	});
    //	return returnValue;
    //},

    /**
     * Extract a model attribute value from a reference doted string.
     *
     * By instance for *mad.model.MyModel.MySubModel.myAttribute* the function will go through
     * each given instance attributes and will extract the leaf value.
     *
     * @param {string} modelRef
     * @param {mad.model.Model} instance
     *
     * @return {mixed}
     */
    getModelAttributeValue: function (modelRef, instance) {
        var returnValue = [],
            attributes = mad.Model.getModelAttributes(modelRef),
            pointer = instance;

        // Go trough the instance attributes and extract the vlaue pointed my the given reference.
        for (var i = 1; i < attributes.length; i++) {
            // If the attribute which owns this current attribute is a model and it is multiple.
            // Extract the attribute value of each instance of the list.
            // @todo this should be the latest attribute of the list.
            if (attributes[i - 1].isMultiple()) {
                returnValue = [];
                pointer.each(function (subInstance) {
                    returnValue.push(subInstance[attributes[i].getName()]);
                });
            } else {
                pointer = can.getObject(attributes[i].getName(), pointer);
                returnValue = pointer;
            }
        }

        return returnValue;
    },

    /**
     * Extract the model attributes from a reference doted string.
     *
     * By instance for *mad.model.MyModel.MySubModel.myAttribute* the function will return :
     * ```
     [ {
    name: 'mad.model.MyModel',
    modelReference: mad.model.MyModel,
    multiple: false
}, {
    name: 'MySubModel',
    modelReference: mad.model.MySubModel,
    multiple: true
}, {
    name: 'myAttribute',
    modelReference: undefined
    multiple: false
} ]
     * ```
     *
     * @param {string} str
     * @return {array|mad.model.Attribute}
     */
    getModelAttributes: function (str) {
        var returnValue = [];

        // Find the root model.
        var matches = str.match(/[\.]?[A-Z][^.]*/),
            modelName = str.substr(0, matches.index + matches[0].length),
            subAttributesStr = str.substr(modelName.length + 1),
            model = can.getObject(modelName);

        returnValue.push(new mad.model.Attribute({
            name: modelName,
            multiple: false,
            modelReference: model
        }));

        // Find the sub-models.
        var subsplit = subAttributesStr.split('.');
        for (var i in subsplit) {
            // Extract the attribute type of the chained attribute in the previously discovered model.
            var attributeType = model.attributes[subsplit[i]],
                name = '',
                multiple = false;

            // The attribute type is a reference to a model.
            if (/models?$/.test(attributeType)) {
                // Extract the model full name.
                var matches = attributeType.match(/(.*)\.models?$/);
                name = subsplit[i];
                model = can.getObject(matches[1]);
                multiple = /models$/.test(attributeType);
            }
            else {
                name = subsplit[i];
                model = undefined;
            }

            returnValue.push(new mad.model.Attribute({
                name: name,
                multiple: multiple,
                modelReference: model
            }));
        }

        return returnValue;
    },

    /**
     * Check if an attribute is a model attribute.
     *
     * @param {string} name The attribute name
     * @return {boolean}
     */
    isModelAttribute: function (name) {
        return /model[s]?$/.test(this.attributes[name]);
    },

    /**
     * Check if an attribute is a model attribute with a multiple cardinality.
     *
     * @param {string} name The name of the attribute to test
     *
     * @return {boolean}
     * @todo Rename this function
     */
    isMultipleAttribute: function (name) {
        return /models$/.test(this.attributes[name]);
    },

    parseModel: function (data, xhr) {
        data = data || {};

        // if the provided data are formated as ajax server response
        if (mad.net.Response.isResponse(data)) {
            console.debug('mad.model.parseModel : mad.net.Response.isResponse == true');
            data = mad.net.Response.getData(data);
            // serialize the data from cake to can format
            data = mad.model.serializer.CakeSerializer.from(data, this);
        } else if (data[this.shortName]) {
            // serialize the data from cake to can format
            data = mad.model.serializer.CakeSerializer.from(data, this);
        }

        return data;
    },

    ///**
    // * Override the can model class to manage our custom server response format
    // * and the CakePHP format. The findAll ajax request is overrided by canJS to
    // * use this function and map server response to the caller model.
    // * @return {Array} a [can.Model.List] of instances. Each instance is created with
    // * [mad.model.Model.model].
    // */
    //'models': function (data) {
    //    // if no data provided, make the models function returning an empty list of the target model
    //    if(typeof data == 'undefined' || data == null) {
    //        data = [];
    //    }
    //    // if the provided data are formated as ajax server response
    //    if (mad.net.Response.isResponse(data)) {
    //        return can.Model.models.call(this, mad.net.Response.getData(data));
    //    }
    //    return can.Model.models.call(this, data);
    //},
    //
    ///**
    // * Override the can model class to manage our custom server response format
    // * and the cakePHP format. The findOne ajax request is overrided by canJS to
    // * use this function and map server response to the caller model.
    // * @return {mad.model.Model}
    // */
    //'model': function (data) {
    //    data = data || {};
    //
    //    // if the provided data are formated as ajax server response
    //    if (mad.net.Response.isResponse(data)) {
    //        data = mad.net.Response.getData(data);
    //        // serialize the data from cake to can format
    //        data = mad.model.serializer.CakeSerializer.from(data, this);
    //    } else if (data[this.shortName]) {
    //        // serialize the data from cake to can format
    //        data = mad.model.serializer.CakeSerializer.from(data, this);
    //    }
    //
    //    // Apply the can model func on the data.
    //    var instance = can.Model.model.call(this, data);
    //
    //    // If we want to force the caching.
    //    if (this.forceStore) {
    //        var i = mad.model.List.indexOf(this.madStore, instance.id);
    //        if (i == -1) {
    //            this.madStore.push(instance);
    //        }
    //    }
    //
    //    return instance;
    //},

    /**
     * Get the model validation rules
     *
     * @param {string} validationCase (optional) The target validation case.
     *
     * @return {array}
     */
    getValidationRules: function (validationCase) {
        var rules = {},
            self = this;

        // Validation case.
        if (typeof validationCase == 'undefined'
            || validationCase == null) {
            validationCase = 'default';
        }

        // The model contains its own validation rules.
        if (!_.isEmpty(this.validationRules)) {
            rules = this.validationRules;
        }
        // Else check if some server rules have been defined.
        else if (this.checkServerRules) {
            // If no rules have been defined for the current model.
            if (typeof this.serverValidationRules[this.shortName] == 'undefined') {
                this.serverValidationRules[this.shortName] = {};
            }

            // If no rules have been defined for the given case.
            if (typeof this.serverValidationRules[this.shortName][validationCase] == 'undefined') {
                // Build the url.
                var url = APP_URL + 'validation/' + this.shortName + '/' + validationCase + '.json';
                // Get the rules from the server.
                self.serverValidationRules[self.shortName][validationCase] = {};
                mad.net.Ajax.request({
                    async: false,
                    type: 'GET',
                    url: url
                }).then(function (data) {
                    self.serverValidationRules[self.shortName][validationCase] = data;
                });
            }
            rules = this.serverValidationRules[this.shortName][validationCase];
        }

        return rules;
    },

    /**
     * Is the field required
     * @param {string} attrName The name of the attribute to check for
     * @param {string} validationCase The validation case to check if the attribute is required
     * @return {bool}
     */
    isRequired: function (attrName, validationCase) {
        var required = false;
        var rules = this.getValidationRules(validationCase);

        // The rule is not define as "fieldName" => "ruleName"
        if (!$.isArray(rules[attrName])) {
            // One rule defined.
            if (typeof rules[attrName]['rule'] != 'undefined') {
                var fieldRequiredForCase = typeof(rules[attrName]['required']) != 'undefined'
                    && (typeof(rules[attrName]['required']) === true
                    || rules[attrName]['required'] === validationCase);
                var fieldAllowEmpty = typeof(rules[attrName]['allowEmpty']) != 'undefined' ?
                    rules[attrName]['allowEmpty'] : true;
                // The case is specifically given as per the cakePHP style.
                if (fieldRequiredForCase === true || fieldAllowEmpty === false) {
                    required = true;
                }
            }
            // Multiple rules defined.
            else {
                for (var ruleLabel in rules[attrName]) {
                    var fieldRequiredForCase = typeof(rules[attrName][ruleLabel]['required']) != 'undefined'
                        && (typeof(rules[attrName][ruleLabel]['required']) === true
                        || rules[attrName][ruleLabel]['required'] === validationCase);
                    var fieldAllowEmpty = typeof(rules[attrName][ruleLabel]['allowEmpty']) != 'undefined' ?
                        rules[attrName][ruleLabel]['allowEmpty'] : true;
                    // The case is specifically given as per the cakePHP style.
                    if (fieldRequiredForCase === true || fieldAllowEmpty === false) {
                        required = true;
                    }
                }
            }
        }
        return required;
    },

    ///**
    // * Transform a nested model instance in flat list
    // * @param {mad.model.Model} instance The target model instance to transform
    // * @param {string} attrName The name of the attribute which store the nested data
    // * @param {string} key (optional) If the key is defined the result will be filled with
    // * the properties named with the key, else the result will be filled with the instance
    // * @return {mad.model.Model.List}
    // */
    //nestedToList: function (instance, attrName, key, _loop) {
    //	var returnValue = [];
    //	key = (typeof key == 'undefined') ? null : key;
    //
    //	if (key != null){
    //		returnValue.push(instance.attr(key));
    //	} else {
    //		returnValue.push(instance);
    //	}
    //
    //	can.each(instance[attrName], function (subInstance, i) {
    //		$.merge(returnValue, mad.model.Model.nestedToList(subInstance, attrName, key, true));
    //	});
    //
    //	if (!_loop) {
    //		var Class = instance.getClass();
    //		returnValue = new Class.List(returnValue);
    //	}
    //	return returnValue;
    //},
    //
    ///**
    // * Transform a nested list of model instances in flat list
    // * @param {mad.model.Model} instance The target model instances to transform
    // * @param {string} attrName The name of the attribute which store the nested data
    // * @return {mad.model.Model.List}
    // */
    //nestedListToList: function (instances, attrName) {
    //	var returnValue = null,
    //		data = [];
    //	can.each(instances, function (instance, i) {
    //		$.merge(data, mad.model.Model.nestedToList(instance, attrName, true));
    //	});
    //
    //	// if the input is not empty, transform the output in list
    //	if (instances.length) {
    //		var Class = instances[0].getClass();
    //		returnValue = new Class.List(data);
    //	}
    //
    //	return returnValue;
    //},
    //
    /**
    * Get all model instances in an array which match the search parameters.
     *
    * @param {array} data The array to search in
    * @param {string} key The key to search
    * @param {string} value The value of the key to search
    * @return {array}
    */
    search: function (data, key, value) {
    	var returnValue = [],
    		split = key.split('.'),
    		modelName = split[0],
    		attrName = split[1];

    	for (var i in data) {
    		if ($.isArray(data[i][modelName])) {
    			for (var j in data[i][modelName]) {
    				if (data[i][modelName][j][attrName] == value) {
    					returnValue.push(data[i]);
    				}
    			}
    		} else {
    			if (data[i][modelName][attrName] == value) {
    				returnValue.push(data[i]);
    			}
    		}
    		// search in children
    		if (data[i].children) {
    			var childrenSearch = mad.model.Model.search (data[i].children, key, value);
    			if (childrenSearch.length) {
    				returnValue = $.merge (returnValue, childrenSearch);
    			}
    		}
    	}
    	return returnValue;
    },

    /**
    * Get on model instance in an array which match the search parameters
    * and its value
    * @param {array} data The array to search in
    * @param {string} key The key to search
    * @param {string} value The value of the key to search
    * @return {mad.model.Model}
    */
    searchOne: function (data, key, value) {
    	var returnValue = null;
    	var searchResults = mad.Model.search(data, key, value);
    	if (searchResults.length) {
    		returnValue = searchResults[0];
    	}
    	return returnValue;
    },

    ///**
    // * Override the serialize feature used when serializing model (server communication,
    // * backup) to support specific attributes format such as date.
    // */
    //serialize : {
    //	/**
    //	 * Support of the specific date format
    //	 * @param {mixed} val The date to serialize
    //	 * @param {string} type The data type (here date)
    //	 * @return {string}
    //	 */
    //	date : function( val, type ){
    //		if (typeof val == 'number' || typeof val == "string") {
    //			val = new Date(val);
    //		}
    //		return val.getYear() + "-" + (val.getMonth() + 1) + "-" + val.getDate() + " " + val.getHours() + ":" + val.getMinutes() + ":" + val.getMilliseconds();
    //	}
    //},

    /**
     * Validate an attribute value with a model attribute rule.
     *
     * @param {string} attrName The attribute name
     * @param {mixed} value The attribute value
     * @param {array} values The model attributes values
     * @param {string} case The case to validate the attribute for
     *
     * @return {array} list of error messages, or empty array if no validation error.
     */
    validateAttribute: function (attrName, value, values, validationCase) {
		var returnValue = [];

		if (typeof validationCase == 'undefined') {
			validationCase = 'default';
		}

		var rules = this.getValidationRules(validationCase);
		if (typeof rules[attrName] != 'undefined') {
			// Is the field required?
			var required = this.isRequired(attrName, validationCase);
			// Is the field passing the required validation.
			var requiredValidation = mad.Validation.validate('required', value);

			// If the field is required & doesn't pass the required validation return an error.
			if (required && requiredValidation !== true) {
				returnValue.push(requiredValidation);
				return returnValue;
			}
			// If the filed is not required and doesn't pass the required the validation
			// the system won't process the other constraints.
			else if (!required && requiredValidation !== true) {
				return returnValue;
			}

			// Otherwise execute all the constraints.
			var attributeRules = rules[attrName];
			// if ($.isArray(attributeRules)) {
			for (var i in attributeRules) {
				var validateResult = mad.Validation.validate(attributeRules[i], value, values);
				if (validateResult !== true) {
					returnValue.push(validateResult);
				}
			}
		}

		return returnValue;
    }

}, /** @prototype */ {

    ///**
    // * Get the object Class.
    // * @return {can/construct}
    // */
    //getClass: function () {
    //	return this.constructor;
    //}

});

export default Model;
