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
	'lib/xregexp/xregexp-all-min.js',
	'jquery',
	'jquery/class',
	'jquery/controller',
	'jquery/model',
	'jquery/view/ejs',
	MAD_ROOT + '/core/class.js'
).then(
	MAD_ROOT + '/bootstrap/appBootstrap.js',
	MAD_ROOT + '/error/exception.js',
	MAD_ROOT + '/error/errorHandler.js',
	MAD_ROOT + '/controller/appController.js',
	MAD_ROOT + '/controller/controller.js',
	MAD_ROOT + '/controller/componentController.js',
	MAD_ROOT + '/controller/component/buttonController.js',
	MAD_ROOT + '/controller/component/containerController.js',
	MAD_ROOT + '/controller/component/gridController.js',
	MAD_ROOT + '/controller/component/contextualMenuController.js',
	MAD_ROOT + '/controller/component/dropDownMenuController.js',
	MAD_ROOT + '/controller/component/menuController.js',
	MAD_ROOT + '/controller/component/popupController.js',
	MAD_ROOT + '/controller/component/tabController.js',
	MAD_ROOT + '/controller/component/workspaceController.js',
	MAD_ROOT + '/controller/component/treeController.js',
	MAD_ROOT + '/controller/component/dynamicTreeController.js',
	MAD_ROOT + '/core/singleton.js',
	MAD_ROOT + '/event/eventBus.js',
	MAD_ROOT + '/form/feedbackController.js',
	MAD_ROOT + '/form/formController.js',
	MAD_ROOT + '/form/formElement.js',
	MAD_ROOT + '/form/formChoiceElement.js',
	MAD_ROOT + '/form/element/dateController.js',
	MAD_ROOT + '/form/element/dropdownController.js',
	MAD_ROOT + '/form/element/checkboxController.js',
	MAD_ROOT + '/form/element/radiobuttonController.js',
	MAD_ROOT + '/form/element/textboxController.js',
	MAD_ROOT + '/helper/controllerHelper.js',
	MAD_ROOT + '/helper/htmlHelper.js',
	MAD_ROOT + '/helper/routeHelper.js',
	MAD_ROOT + '/helper/componentHelper.js',
	MAD_ROOT + '/helper/component/boxDecorator.js',
	MAD_ROOT + '/lang/i18n.js',
	MAD_ROOT + '/model',
	MAD_ROOT + '/model/menuItem.js',
	MAD_ROOT + '/model/state.js',
	MAD_ROOT + '/model/validationRules.js',
	MAD_ROOT + '/net',
	MAD_ROOT + '/object/map.js',
	MAD_ROOT + '/string/uuid.js',
	MAD_ROOT + '/route/routeListener.js',
	MAD_ROOT + '/route/dispatcherInterface.js',
	MAD_ROOT + '/route/extensionControllerActionDispatcher.js',
	MAD_ROOT + '/view/view.js'
	//    MAD_ROOT + '/route/pageDispatcher.js',
);