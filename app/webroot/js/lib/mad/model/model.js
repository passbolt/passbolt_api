steal(
	'jquery/model',
	'can/model',
	'mad/model/list.js',
	'mad/model/serializer/cakeSerializer.js'
).then(function () {

	/*
	 * @class mad.model.Model
	 * @inherits jQuery.Model
	 * @parent mad.core
	 * 
	 * The core class Model is an extension of the JavascriptMVC Model. This
	 * class allow us to easily hook common behavior, such as :
	 * <ul>
	 *	<li>
	 *		validating model attribute
	 *	</li>
	 * </ul>
	 * 
	 * @constructor
	 * creates a new model
	 * @return {mad.model.Model}
	 */
	can.Model('mad.model.Model', /** @static */ {

		/**
		 * Force the storing of this model's instances.
		 * @type boolean
		 * @protected
		 */
		'forceStore': false,

		/**
		 * Store all the instance of this model in the local store.
		 * @type boolean
		 * @protected
		 */
		'madStore': new can.Model.List(),

		/**
		 * The model attributes validation rules.
		 * @type array
		 * @protected
		 */
		'validationRules': {},

		/**
		 * The model attributes validation rules defined on the server.
		 * @type array
		 * @protected
		 */
		'serverValidationRules': {},

		/**
		 * Check the server validation rules.
		 * @type boolean
		 * @protected
		 */
		'checkServerRules': true,

		/**
		 * Get deep attribute value(s) functions of a string which defines the full path
		 * of the attribute. By instance : ns.MyModel.MySubmodel.myAttribute
		 * @param {string} modelRef A string which defines the full path of the attribute
		 * @param {mad.model.Model} instance The instance to work on it
		 * @return {mixed}
		 */
		'getModelAttributeValue': function(modelRef, instance) {
			/*
			 * Get models referenced by the model reference
			 * by instance ns.model.submodels.attribute, you will get an array of 3 mad.model.Attribute
			 * object which will carry data about the reference models
			 */
			var models = mad.model.Model.getModelAttributes(modelRef),
				// Path of the attribute in the parent model
				attrPath = '',
				// Attribute name
				attrName = models[models.length - 1].name,
				// Value of the attribute
				returnValue = null;

			// build the attribute path
			for (var i = 1; i<models.length-1; i++) {
				attrPath += attrPath.length ? '.' + models[i].name : models[i].name;
			}
			// extract the model attribute
			var modelAttr = can.getObject(attrPath, instance);
			if (typeof modelAttr != 'undefined') {
				// if multiple association
				if (modelAttr.length) {
					returnValue = [];
					can.each(modelAttr, function(attr, i) {
						returnValue.push(attr[attrName]);
					});
				} else {
					returnValue = modelAttr[attrName];
				}
			}

			return returnValue;
		},

		/**
		 * Check if an attribute is multiple or not. A multiple attribute is by definition
		 * a multiple association to another model
		 * @param {string} attrName The name of the attribute to test
		 * @return {boolean}
		 */
		'isMultipleAttribute': function (attrName) {
			return /models$/.test(this.attributes[attrName]);
		},

		/**
		 * Get attribute description of this model
		 * @param {string} name The name of the attribute
		 * @return {mad.model.Attribute}
		 */
		'getAttribute': function (name) {
			var returnValue = null,
				modelName = this.attributes[name].substr(0, this.attributes[name].lastIndexOf('.')),
				model = $.String.getObject(modelName);
			returnValue = new mad.model.Attribute({
				'name': name,
				'multiple': this.isMultipleAttribute(name),
				'modelReference': model
			});
			return returnValue;
		},

		/**
		 * Extract the model attributes functions of the given string following the definition of the 
		 * models and submodels.
		 * By instance for ns.Model.Submodels.attribute you will get :
		 * @codestart
[
	{
		name: ns.Model,
		label: 'ns.Model',
		multiple: false
	}, 
	{
		name: ns.SubModels,
		label: 'subModels',
		multiple: true
	}, 
	{
		name: attribute,
		multiple: false
	}
]
		 * @codeend 
		 * 
		 * @param {string} str The string to work on
		 * @return {array} Array of mad.model.Attribute
		 */
		'getModelAttributes': function (str) {
			var returnValue = [],
				split = str.split('.');

			for (var i in split) {
				// get the top model reference
				if (!returnValue.length) {
					// the top model has a upper case first character
					// it is important to respect the wording (package lowcase, and Class
					// first char upcase)
					if (split[i][0] === split[i][0].toUpperCase()) {
						var modelName = split.slice(0, parseInt(i)+1).join('.');
						var model = $.String.getObject(modelName);
						returnValue.push(new mad.model.Attribute({
							'name': modelName,
							'multiple': false,
							'modelReference': model
						}));
					}
				} else {
					// after we found the top model reference, check for sub models
					var ownerModel = returnValue[returnValue.length - 1].modelReference;
					// if the current split is a reference to a submodel
					if (ownerModel.attributes[split[i]]) {
						var attrName = ownerModel.attributes[split[i]];
						var modelName = attrName.slice(0,attrName.lastIndexOf('.'));
						var model = $.String.getObject(modelName);
						returnValue.push(new mad.model.Attribute({
							'name': split[i],
							'multiple': /models$/.test(attrName),
							'modelReference': model
						}));
					} else {
						// else the split is a reference to a scalar attribute
						returnValue.push(new mad.model.Attribute({
							'name': split[i],
							'modelReference': null
						}));
						break;
					}
				}
			}
			return returnValue;
		},

		/**
		 * Check if an attribute is an associated model attribute
		 * @param {string} name The attribute name
		 * @return {boolean}
		 */
		'isModelAttribute': function (name) {
			return /model[s]?$/.test(this.attributes[name]);
		},

		/**
		 * Override the can model class to manage our custom server response format 
		 * and the CakePHP format. The findAll ajax request is overrided by canJS to
		 * use this function and map server response to the caller model.
		 * @return {Array} a [can.Model.List] of instances. Each instance is created with
		 * [mad.model.Model.model].
		 */
		'models': function (data) {
			// if no data provided, make the models function returning an empty list of the target model
			if(typeof data == 'undefined' || data == null) {
				data = [];
			}
			// if the provided data are formated as ajax server response
			if (mad.net.Response.isResponse(data)) {
				return can.Model.models.call(this, mad.net.Response.getData(data));
			}
			return can.Model.models.call(this, data);
		},

		/**
		 * Override the can model class to manage our custom server response format
		 * and the cakePHP format. The findOne ajax request is overrided by canJS to
		 * use this function and map server response to the caller model.
		 * @return {mad.model.Model}
		 */
		'model': function (data) {
			data = data || {};

			// if the provided data are formated as ajax server response
			if (mad.net.Response.isResponse(data)) {
				data = mad.net.Response.getData(data);
				// serialize the data from cake to can format
				data = mad.model.serializer.CakeSerializer.from(data, this);
			} else if (data[this.shortName]) {
				// serialize the data from cake to can format
				data = mad.model.serializer.CakeSerializer.from(data, this);
			}

			// Apply the can model func on the data.
			var instance = can.Model.model.call(this, data);

			// If we want to force the caching.
			if (this.forceStore) {
				var i = mad.model.List.indexOf(this.madStore, instance.id);
				if (i == -1) {
					this.madStore.push(instance);
				}
			}

			return instance;
		},

		/**
		 * Get the model validation rules
		 * @param {string} validationCase (optional) The target validation case.
		 * @return {array}
		 */
		'getValidationRules': function (validationCase) {
			var rules = {},
				self = this;

            // Validation case.
            if (typeof validationCase == 'undefined'
                || validationCase == null) {
                validationCase = 'default';
            }

			// The model contains its own validation rules.
			if (this.validationRules.length > 0) {
				rules = this.validationRules;
			}
			// Else check if some server rules have been defined.
			else if (this.checkServerRules) {
                // If no rules have been defined for the current model.
                if(typeof this.serverValidationRules[this.shortName] == 'undefined') {
                    this.serverValidationRules[this.shortName] = {};
                }
				// If no rules have been defined for the given case.
				if (typeof this.serverValidationRules[this.shortName][validationCase] == 'undefined') {
                    // Build the url.
                    var url = APP_URL + 'validation/' + this.shortName + '/' + validationCase + '.json';
                    // Get the rules from the server.
					self.serverValidationRules[self.shortName][validationCase] = {};
					mad.net.Ajax.request({
						'async': false,
						'type': 'GET',
						'url': url
					}).then(function(data) {
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
		'isRequired': function (attrName, validationCase) {
			var required = false;
			var rules = this.getValidationRules(validationCase);

			// The rule is not define as "fieldName" => "ruleName"
			if (!$.isArray(rules[attrName])) {
				// One rule defined.
				if (typeof rules[attrName]['rule'] != 'undefined') {
					// The case is specifically given as per the cakePHP style.
					if (typeof(rules[attrName]['required']) != 'undefined'
						&& (typeof(rules[attrName]['required']) === true
							|| rules[attrName]['required'] === validationCase)) {
						required = true;
					}
				}
				// Multiple rules defined.
				else {
					for (var ruleLabel in rules[attrName]) {
						// The case is specifically given as per the cakePHP style.
						if (typeof(rules[attrName][ruleLabel]['required']) != 'undefined'
							&& (typeof(rules[attrName][ruleLabel]['required']) === true
								|| rules[attrName][ruleLabel]['required'] === validationCase)) {
							required = true;
						}
					}
				}
			}

			return required;
		},

		/**
		 * Validate an attribute
		 * @param {string} attrName The attribute name
		 * @param {mixed} value The attribute value
		 * @param {array} values The model attributes values
		 * @param {string} case The case to validate the attribute for
		 * @return {boolean}
		 */
		'validateAttribute': function (attrName, value, values, validationCase) {
			var returnValue = true;

			if(typeof validationCase == 'undefined') {
				validationCase = 'default';
			}

			var rules = this.getValidationRules(validationCase);
			if (typeof rules[attrName] != 'undefined') {
				// Is the field required?
				var required = this.isRequired(attrName, validationCase);
				// Is the field passing the required validation.
				var requiredValidation = mad.model.ValidationRules.validate('required', value);

				// If the field is required & doesn't pass the required validation return an error.
				if (required && requiredValidation !== true) {
					return requiredValidation;
				}
				// If the filed is not required and doesn't pass the required the validation
				// the system won't process the other constraints.
				else if (!required && requiredValidation !== true) {
					return true;
				}

				// Otherwise execute all the constraints.
				var attributeRules = rules[attrName];
				// if ($.isArray(attributeRules)) {
				for (var i in attributeRules) {
					var validateResult = mad.model.ValidationRules.validate(attributeRules[i], value, values);
					if (validateResult !== true) {
						if (returnValue === true) {
							returnValue = '';
						}
						returnValue += validateResult;
					}
				}
				// } else {
				// 	returnValue = mad.model.ValidationRules.validate(attributeRules, value, modelValues);
				// }
			}

			return returnValue;
		},

		/**
		 * Transform a nested model instance in flat list
		 * @param {mad.model.Model} instance The target model instance to transform
		 * @param {string} attrName The name of the attribute which store the nested data
		 * @param {string} key (optional) If the key is defined the result will be filled with
		 * the properties named with the key, else the result will be filled with the instance
		 * @return {mad.model.Model.List} 
		 */
		'nestedToList': function (instance, attrName, key, _loop) {
			var returnValue = [];
			key = (typeof key == 'undefined') ? null : key;
			
			if (key != null){
				returnValue.push(instance.attr(key));
			} else {
				returnValue.push(instance);
			}
			
			can.each(instance[attrName], function (subInstance, i) {
				$.merge(returnValue, mad.model.Model.nestedToList(subInstance, attrName, key, true));
			});

			if (!_loop) {
				var Class = instance.getClass();
				returnValue = new Class.List(returnValue);
			}
			return returnValue;
		},

		/**
		 * Transform a nested list of model instances in flat list
		 * @param {mad.model.Model} instance The target model instances to transform
		 * @param {string} attrName The name of the attribute which store the nested data
		 * @return {mad.model.Model.List} 
		 */
		'nestedListToList': function (instances, attrName) {
			var returnValue = null,
				data = [];
			can.each(instances, function (instance, i) {
				$.merge(data, mad.model.Model.nestedToList(instance, attrName, true));
			});

			// if the input is not empty, transform the output in list
			if (instances.length) {
				var Class = instances[0].getClass();
				returnValue = new Class.List(data);
			}

			return returnValue;
		},

		/**
		 * Get all model instances in an array which match the search paramaters
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {array}
		 */
		'search': function (data, key, value) {
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
		'searchOne': function (data, key, value) {
			var returnValue = null;
			var searchResults = mad.model.Model.search(data, key, value);
			if (searchResults.length) {
				returnValue = searchResults[0];
			}
			return returnValue;
		},

		/**
		 * Override the serialize feature used when serializing model (server communication,
		 * backup) to support specific attributes format such as date.
		 */
		'serialize' : {
			/**
			 * Support of the specific date format
			 * @param {mixed} val The date to serialize
			 * @param {string} type The data type (here date)
			 * @return {string}
			 */
			date : function( val, type ){
				if (typeof val == 'number' || typeof val == "string") {
					val = new Date(val);
				}
				return val.getYear() + "-" + (val.getMonth() + 1) + "-" + val.getDate() + " " + val.getHours() + ":" + val.getMinutes() + ":" + val.getMilliseconds();
			}
		}

	}, /** @prototype */ {

		/**
		* Get the Class of the instance
		* @return {can/construct}
		*/
		'getClass': function () {
			return this.constructor;
		}

	});

});