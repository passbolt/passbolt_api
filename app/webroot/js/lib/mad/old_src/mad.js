/*
 * @page index Mad Squirrel
 * @tag home
 *
 * <p style="text-align:center; padding-top:75px; width:100%;">
 *	<img src="./logo.png" style="width:128px; height:128px;"/>
 *	<h1 style="text-align:center;">
 *		Passbolt Javascript
 *	</h1>
 *	<h1 style="text-align:center;">
 *		Documentation
 *	</h1>
 * </p>
 * 
 */

steal(
	'jquery/class',
	// Need to load our overriding of Class before other classes which inherit from it.
	'mad/core/class.js'
).then(
	// load jquerypp libraries
	'jquery/controller',
	'jquery/model',
	'jquery/view/ejs',
	// load mad core libraries
	'mad/controller',
	'mad/model',
	'mad/model/attribute.js',
	'mad/view'
).then(
	'mad/array/array.js',
	'mad/bootstrap/appBootstrap.js',
	'mad/config/config.js',
	'mad/error/exception.js',
	'mad/error/errorHandler.js',
	'mad/controller/appController.js',
	'mad/controller/componentController.js',
	'mad/controller/component/buttonController.js',
	'mad/controller/component/buttonDropdownController.js',
	'mad/controller/component/compositeController.js',
	'mad/controller/component/dialogController.js',
	'mad/controller/component/freeCompositeController.js',
	'mad/controller/component/gridController.js',
	'mad/controller/component/contextualMenuController.js',
	'mad/controller/component/dropDownMenuController.js',
	'mad/controller/component/menuController.js',
	'mad/controller/component/tabController.js',
	'mad/controller/component/toggleButtonController.js',
	'mad/controller/component/treeController.js',
	'mad/controller/component/dynamicTreeController.js',
	'mad/core/singleton.js',
	'mad/crypto/random.js',
	'mad/event/event.js',
	'mad/event/eventBus.js',
	'mad/form/feedbackController.js',
	'mad/form/formController.js',
	'mad/form/formElement.js',
	'mad/form/formChoiceElement.js',
	'mad/form/element/autocompleteController.js',
	'mad/form/element/checkboxController.js',
	'mad/form/element/dateController.js',
	'mad/form/element/dropdownController.js',
	'mad/form/element/freeElementController.js',
	'mad/form/element/radiobuttonController.js',
	'mad/form/element/textboxController.js',
	'mad/helper/controllerHelper.js',
	'mad/helper/htmlHelper.js',
	'mad/helper/routeHelper.js',
	'mad/helper/componentHelper.js',
	'mad/helper/component/boxDecorator.js',
	'mad/lang/i18n.js',
	'mad/model/action.js',
	'mad/model/state.js',
	'mad/model/validationRules.js',
	'mad/net/ajax.js',
	'mad/object/map.js',
	'mad/string/uuid.js',
	'mad/route/routeListener.js',
	'mad/route/dispatcherInterface.js',
	'mad/route/extensionControllerActionDispatcher.js'
	//    'mad/route/pageDispatcher.js',
);