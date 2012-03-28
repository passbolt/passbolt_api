/*
* jQuery modalBox plugin v1.2.0 <http://code.google.com/p/jquery-modalbox-plugin/> 
* @requires jQuery v1.3.2 or later 
* is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
(function($){
	
	
	// Default options
	var defaults = {
		
		minimalTopSpacingOfModalbox 		: 50, // sets the minimum space between modalbox and visible area in the browser window
		usejqueryuidragable					: false, //options: true, false (the modalbox is draggable, Requires jQuery v1.2.6 or later, jQuery UI  and components: jQuery UI Widget, jQuery UI Mouse, jQuery UI Draggable)
		killModalboxWithCloseButtonOnly		: false, // options: true, false (close the modal box with close button only)
		setWidthOfModalLayer				: null,
		customClassName 					: null,
		getStaticContentFrom				: null,
		
		// set the positions of the modalbox manualy
		positionLeft 						: null,
		positionTop 						: null,
		
		// effects
		effectType_show_fadingLayer			: ['fade', 'fast'], // options: ['show'] or ['fade', 'fast']
		effectType_hide_fadingLayer 		: ['fade', 'fast'], // options: ['hide'] or ['fade', 'fast']
		effectType_show_modalBox 			: ['show'], // options: ['show'] or ['fade', 'fast']
		effectType_hide_modalBox 			: ['hide'], // options: ['hide'] or ['fade', 'fast']
		
		// selectors
		selectorModalboxContainer			: '#modalBox',
		selectorModalboxBodyContainer		: '#modalBoxBody',
		selectorModalboxBodyContentContainer: '.modalBoxBodyContent',
		selectorFadingLayer					: '#modalBoxFaderLayer',
		selectorAjaxLoader					: '#modalBoxAjaxLoader',
		selectorModalboxCloseContainer 		: '#modalBoxCloseButton',
		selectorModalboxContentContainer	: '.modalboxContent',
		selectorHiddenAjaxInputField		: 'ajaxhref',
		selectorPreCacheContainer			: '#modalboxPreCacheContainer',
		selectorImageGallery				: '.modalgallery',
		
		/*
			Layout Container:
			--------------------------------------------
			<div class="modalboxStyleContainer_surface_left">
				<div class="modalboxStyleContainer_surface_right">
					<div class="modalboxStyleContainerContent">
						<div class="modalBoxBodyContent">
							
							Content
							
						</div>
					</div>
				</div>
			</div>

			<div class="modalboxStyleContainer_corner_topLeft"><!-- - --></div>
			<div class="modalboxStyleContainer_corner_topRight"><!-- - --></div>

			<div class="modalboxStyleContainer_corner_bottomLeft"><!-- - --></div>
			<div class="modalboxStyleContainer_corner_bottomRight"><!-- - --></div>

			<div class="modalboxStyleContainer_surface_top"><div class="modalboxStyleContainer_surface_body"><!-- - --></div></div>
			<div class="modalboxStyleContainer_surface_bottom"><div class="modalboxStyleContainer_surface_body"><!-- - --></div></div>
		*/
		setModalboxLayoutContainer_Begin	: '<div class="modalboxStyleContainer_surface_left"><div class="modalboxStyleContainer_surface_right"><div class="modalboxStyleContainerContent"><div class="modalBoxBodyContent">',
		setModalboxLayoutContainer_End		: '</div></div></div></div><div class="modalboxStyleContainer_corner_topLeft"><!-- - --></div><div class="modalboxStyleContainer_corner_topRight"><!-- - --></div><div class="modalboxStyleContainer_corner_bottomLeft"><!-- - --></div><div class="modalboxStyleContainer_corner_bottomRight"><!-- - --></div><div class="modalboxStyleContainer_surface_top"><div class="modalboxStyleContainer_surface_body"><!-- - --></div></div><div class="modalboxStyleContainer_surface_bottom"><div class="modalboxStyleContainer_surface_body"><!-- - --></div></div>',
		
		// localization
		localizedStrings					: {
			messageCloseWindow					: 'Close Window',
			messageAjaxLoader					: 'Please wait',
			errorMessageIfNoDataAvailable		: '<strong>No content available!</strong>',
			errorMessageXMLHttpRequest			: 'Error: XML-Http-Request Status "500"',
			errorMessageTextStatusError			: 'Error: AJAX Request failed'
		},
		
		setTypeOfFadingLayer				: 'black', // options: white, black, custom, disable
		setStylesOfFadingLayer				: {// define the opacity and color of fader layer here
			white			: 'background-color:#fff; filter:alpha(opacity=60); -moz-opacity:0.6; opacity:0.6;',
			black			: 'background-color:#000; filter:alpha(opacity=40); -moz-opacity:0.4; opacity:0.4;',
			transparent 	: 'background-color:transparent;',
			custom			: null
		},
		
		// direct call
		directCall							: {
			source 	: null, // put url here like http://www.yourdomain.de/test?param=1&param=2
			data	: null, // put content here like data : '<div class="testclass">test</div>'
			element	: null // define identifyer of source container here to get html content, can be id or class like  like '#sourcecontainer'
		},
		
		// ajax settings
		ajax_type							: 'POST', // The type of request to make ("POST" or "GET"), default is "POST". Note: Other HTTP request methods, such as PUT and DELETE, can also be used here, but they are not supported by all browsers.
		ajax_contentType					: 'application/x-www-form-urlencoded; charset=utf-8', // examples : charset=utf-8, charset=ISO-8859-1
		
		// callback functionalities
		callFunctionBeforeShow : function(){ // call a custom function before layer will be shown. return value must be "true" to finalize modal layer
			return true;
		},
		callFunctionAfterShow 				: function(){}, // call a custom function after layer is shown
		callFunctionBeforeHide 				: function(){}, // call a custom function before layer will be closed
		callFunctionAfterHide 				: function(){} // call a custom function after layer is closed
		
	};
	
	
	try{
		
		/*
			merge the custom settings with plugin defaults / Example:
			---------------------------------------------------------
			<head>
				<script type="text/javascript">
					var modalboxGlobalDefaults = {
						localizedStrings					: {
							messageCloseWindow				: 'Fenster schliessen',
							messageAjaxLoader				: 'Bitte warten<br />Ihre Anfrage wird verarbeitet.',
							errorMessageIfNoDataAvailable	: '<strong>Keine Inhalte verf&uuml;gbar!</strong>',
							errorMessageXMLHttpRequest		: 'Ein technischer Fehler (XML-Http-Request Status "500") verhindert den Aufruf der Seite.<br /><br />Bitte versuchen Sie es sp&auml;ter noch einmal',
							errorMessageTextStatusError		: 'Ein technischer Fehler (AJAX-Anfrage fehlgeschlagen) verhindert den Aufruf der Seite.<br /><br />Bitte versuchen Sie es sp&auml;ter noch einmal'
						}
					}
				</script>
			</head>
		*/
		
		defaults = jQuery.extend({}, defaults, modalboxGlobalDefaults);
		
	} catch(error) {}
	
	
	
	var methods = {
		
		/********** init - BEGIN **********/
		init : function( globaloptions ) {
			
			
			// merge the plugin defaults with custom options
			var globaloptions = jQuery.extend({}, defaults, globaloptions);
			
			
			/************ direct call without event binding - BEGIN ************/
			if( globaloptions.directCall ){
				if( globaloptions.directCall["source"] ){
					openModalBox({
						type	: 'ajax',
						source 	: globaloptions.directCall["source"],
						data	: null
					});
				} else if ( globaloptions.directCall["data"] ){
					openModalBox({
						type	: 'static',
						source 	: null,
						data	: globaloptions.directCall["data"]
					});
				} else if ( globaloptions.directCall["element"] ){
					openModalBox({
						type	: 'static',
						source 	: null,
						data	: jQuery( globaloptions.directCall["element"] ).html()
					});
				}
			}
			/************ direct call without event binding - END ************/
			
			
			/************ initializeModalBox - BEGIN ************/
			var doNotBindEventsOnWindowResize = false;
			jQuery(window).resize(function(){
				doNotBindEventsOnWindowResize = true;
			});
			
			if( !doNotBindEventsOnWindowResize ){
				jQuery(this).die("click").live("click", function(event){
					prepareModalbox({
						event 	: event,
						element : jQuery(this)
					});
				});
			}
			/************ initializeModalBox - END ************/
			
			
			
			/************ prepareModalbox - END ************/
			function prepareModalbox(settings){
				
				
				var settings = jQuery.extend({// default settings
					event 	: null,
					element : null
				}, settings || {} );
				
				
				if( settings.event && settings.element ){
					
					var currentEvent 	=  settings.event;
					var elementObj		= settings.element;
					
					var doNotOpenModalBoxContent = false;
					var isFormSubmit = false;
					
					if( elementObj.is("input") ){
						
						var source 		= elementObj.parents('form').attr('action');
						var data		= elementObj.parents('form').serialize();
						var type		= 'ajax';
						isFormSubmit 	= true;
						
						currentEvent.preventDefault();
						
					} else if ( jQuery("input[name$='" + globaloptions.selectorHiddenAjaxInputField + "']", elementObj).length != 0 ) {
						
						var source 		= jQuery("input[name$='" + globaloptions.selectorHiddenAjaxInputField + "']", elementObj).val();
						var data		= '';
						var type		= 'ajax';
						
						currentEvent.preventDefault();
						
					} else if ( jQuery(globaloptions.selectorModalboxContentContainer, elementObj).length != 0 ) {
						
						if ( jQuery(globaloptions.selectorModalboxContentContainer + " img" + globaloptions.selectorImageGallery, elementObj).length != 0 ) {
							var currentImageObj = jQuery(globaloptions.selectorModalboxContentContainer + " img" + globaloptions.selectorImageGallery, elementObj);
						}
						
						var source 		= '';
						var data		= jQuery(globaloptions.selectorModalboxContentContainer, elementObj).html();
						var type		= 'static';
						
						currentEvent.preventDefault();
						
					} else if ( globaloptions.getStaticContentFrom ) {
						
						var source 		= '';
						var data		= jQuery(globaloptions.getStaticContentFrom).html();
						var type		= 'static';
						
						currentEvent.preventDefault();
						
					} else {
						
						doNotOpenModalBoxContent = true;
						
					}
					
					if( !doNotOpenModalBoxContent ){
						openModalBox({
							type				: type,
							element 			: elementObj,
							source 				: source,
							data				: data,
							loadingImagePreparer 	: {
								currentImageObj 	: currentImageObj,
								finalizeModalBox 	: false
							}
						});
					}
					
					if( isFormSubmit ){
						return false;
					}
				}
			}
			/************ prepareModalbox - END ************/
						
			
			
			/************ ajaxRedirect - BEGIN ************/
			function ajaxRedirect(settings){


				var settings = jQuery.extend({// default settings
					ar_XMLHttpRequest	: null,
					ar_textStatus		: null,
					ar_errorThrown		: null,
					targetContainer		: null,
					ar_enableDebugging	: false
				}, settings || {} );
				
				
				// ~~~~~~~~~ global settings - BEGIN ~~~~~~~~~ //
				var XMLHttpRequest 	= settings.ar_XMLHttpRequest;
				var textStatus 		= settings.ar_textStatus;
				var errorThrown 	= settings.ar_errorThrown;
				// ~~~~~~~~~ global settings - END ~~~~~~~~~ //
				
				
				if ( XMLHttpRequest && textStatus != "error" ) {
					
					if( XMLHttpRequest.status == 403 ){
						
						var redirect = XMLHttpRequest.getResponseHeader("Location");
						if( typeof redirect !== "undefined" ) {
							location.href = redirect;
						}
						
					} else if ( XMLHttpRequest.status == 500 && settings.targetContainer ){
						
						addErrorMessage({
							errorMessage 	: globaloptions.localizedStrings["errorMessageXMLHttpRequest"],
							targetContainer	: settings.targetContainer
						});
					}
					
					if( settings.ar_enableDebugging ){
						console.log( "XMLHttpRequest.status: " + XMLHttpRequest.status );
					}
					
				} else if ( textStatus == "error" ) {
					
					if ( settings.targetContainer ){
						addErrorMessage({
							errorMessage 	: globaloptions.localizedStrings["errorMessageTextStatusError"],
							targetContainer	: settings.targetContainer
						});
					}
					
					if( settings.ar_enableDebugging ){
						console.log( "textStatus: " + textStatus );
					}
					
				} else {
					// no errors
				}
				
				
				function addErrorMessage(settings){

					var settings = jQuery.extend({// default settings
						errorMessage 	: null,
						targetContainer	: null
					}, settings || {} );
					
					if( settings.errorMessage && settings.targetContainer ){
						
						var errorMessageContainer	= '';
						errorMessageContainer += '<div class="simleModalboxErrorBox"><div class="simleModalboxErrorBoxContent">';
						errorMessageContainer += settings.errorMessage;
						errorMessageContainer += '</div></div>';
						
						jQuery(settings.targetContainer).removeAttr("style").html( errorMessageContainer );
						if( jQuery(settings.targetContainer).parents(globaloptions.selectorModalboxContainer).length > 0 ){
							jQuery(globaloptions.selectorAjaxLoader).remove();
							centerModalBox();
						}
						
					}
				}
				
				
			}
			/************ ajaxRedirect - END ************/
			
			
			
			/************ addAjaxUrlParameter - BEGIN ************/
			function addAjaxUrlParameter(settings){


				var settings = jQuery.extend({// default settings
					currentURL 			: '',
					addParameterName 	: 'ajaxContent',
					addParameterValue 	: 'true'
				}, settings || {} );
				
				var currentURL = settings.currentURL;
					
				if( currentURL.indexOf(settings.addParameterName) != -1){
					currentURL = currentURL;
				} else {
					if( currentURL.indexOf("?") != -1){
						var currentSeparator = "&";
					} else {
						var currentSeparator = "?";
					}
					currentURL = currentURL + currentSeparator + settings.addParameterName + '=' + settings.addParameterValue;
				}
				
				return currentURL;
				
			}
			/************ addAjaxUrlParameter - END ************/
			
			
			
			/************ imagePreparer - END ************/
			function imagePreparer(settings){
			
				
				var settings = jQuery.extend({
					type				: settings.type,
					element 			: settings.element,
					source 				: settings.source,
					data				: settings.data,
					loadingImagePreparer 	: {
						currentImageObj 	: settings.loadingImagePreparer["currentImageObj"],
						finalizeModalBox 	: settings.loadingImagePreparer["finalizeModalBox"]
					},
					nameOfImagePreloaderContainer 	: "imagePreparerLoader",
					wrapContainer :	'<div class="modalBoxCarouselItemContainer"></div>'
				}, settings || {} );
				
				
				var imageObj = settings.loadingImagePreparer["currentImageObj"];
				
				
				if( imageObj ){
					
					
					/*
					jQuery(globaloptions.selectorModalboxContentContainer).css({ 
						display : "block",
						position : "absolute",
						left : "-9999px",
						top : "-9999px"
					});
					
					var imageObjQuantity = imageObj.length();
					var initCount = 0;
					
					imageObj.load(function(){
						
						initCount++;
						
						if( initCount == imageObjQuantity ){
							return true;
						}
					});
					
					jQuery(globaloptions.selectorModalboxContentContainer).removeAttr("style");
					*/
					
					
					jQuery(globaloptions.selectorModalboxContentContainer).css({ 
						display : "block",
						position : "absolute",
						left : "-9999px",
						top : "-9999px"
					}).removeAttr("style");
					
					
					openModalBox({
						type				: settings.type,
						element 			: settings.element,
						source 				: settings.source,
						data				: settings.data,
						loadingImagePreparer 	: {
							currentImageObj 				: imageObj,
							finalizeModalBox 				: true,
							nameOfImagePreloaderContainer 	: settings.nameOfImagePreloaderContainer
						}
					});
				}
			}
			/************ imagePreparer - END ************/
			
			
			
			/************ openModalBox - BEGIN ************/
			function openModalBox(settings){
			
				var settings = jQuery.extend({
					type				: null,
					element 			: null,
					source 				: null,
					data				: null,
					loadingImagePreparer 	: {
						currentImageObj 				: null,
						finalizeModalBox 				: false,
						nameOfImagePreloaderContainer 	: null
					}
				}, settings || {} );
				
				
				/* init close events - BEGIN */
				function initClose(){
					methods.close({
						callFunctionBeforeHide : globaloptions.callFunctionBeforeHide,
						callFunctionAfterHide : globaloptions.callFunctionAfterHide
					});
				}
				
				if( !globaloptions.killModalboxWithCloseButtonOnly ){
					jQuery(globaloptions.selectorFadingLayer).die("click").live("click", function(){
						initClose();
					});
				}
				
				jQuery(globaloptions.selectorModalboxContainer + " .closeModalBox").die("click").live("click", function(){
					initClose();
				});
				/* init close events - END */
				
				
				jQuery(globaloptions.selectorPreCacheContainer).remove();
				
				
				if( settings.loadingImagePreparer["currentImageObj"] && !settings.loadingImagePreparer["finalizeModalBox"] ){
					
					imagePreparer({
						type					: settings.type,
						element 				: settings.element,
						source 					: settings.source,
						data					: settings.data,
						loadingImagePreparer 	: settings.loadingImagePreparer
					});
					
				} else {
				
					if( settings.type && globaloptions.callFunctionBeforeShow() ){
						
						
						if( settings.source ){
							settings.source = addAjaxUrlParameter({
								currentURL : settings.source
							});
						}
						
						
						var prepareCustomWidthOfModalBox = "";
						var setModalboxClassName = "";
						
						if( settings.element ){
							
							if( jQuery(settings.element).hasClass("large") ){
								setModalboxClassName += 'large';
							} else if( jQuery(settings.element).hasClass("medium") ){
								setModalboxClassName += 'medium';
							} else if( jQuery(settings.element).hasClass("small") ){
								setModalboxClassName += 'small';
							} else if( settings.loadingImagePreparer["nameOfImagePreloaderContainer"] ){
								setModalboxClassName += 'auto modalBoxBodyContentImageContainer';
							}
							
							if( jQuery(settings.element).hasClass("emphasis") ){
								setModalboxClassName += ' emphasis';
							}
						}
						
						
						if( globaloptions.customClassName ){
							setModalboxClassName += ' ' + globaloptions.customClassName;
						}
						
						
						if( globaloptions.setWidthOfModalLayer ){
							prepareCustomWidthOfModalBox += 'width:' + parseInt( globaloptions.setWidthOfModalLayer ) + 'px; ';
						}
						
						
						/*  create Modalbox first - BEGIN */
						if( jQuery(globaloptions.selectorModalboxContainer).length == 0 ){
							
							jQuery("body").append(
								methods.modalboxBuilder({
									customStyles : 'class="' + setModalboxClassName + '" style="' + prepareCustomWidthOfModalBox + '"'
								})
							);
							
						} else {
						
							var prepareNameOfAjaxLoader = methods.cleanupSelectorName({
								replaceValue : globaloptions.selectorAjaxLoader
							});
							
							methods.clean({
								setModalboxContentContainer	: globaloptions.selectorModalboxBodyContentContainer,
								selectorAjaxLoader : prepareNameOfAjaxLoader,
								localizedStrings : globaloptions.localizedStrings["messageAjaxLoader"]
							});
						}
						/*  create Modalbox first - END */
						
						
						var getCurrentContent = function(){
							
							switch (settings.type) {
								
								case 'static': {
									
									jQuery(globaloptions.selectorAjaxLoader).hide();
									
									jQuery(globaloptions.selectorModalboxBodyContentContainer, globaloptions.selectorModalboxContainer).html(
										settings.data
									);
									
									centerModalBox({
										callFunctionAfterShow : globaloptions.callFunctionAfterShow
									});
									
									break;
									
								} case 'ajax': {
								
									jQuery.ajax({
										type : globaloptions.ajax_type,
										url	: settings.source,
										data : settings.data,
										contentType : globaloptions.ajax_contentType,
										success	: function(data, textStatus){
											
											jQuery(globaloptions.selectorAjaxLoader).fadeOut("fast", function(){
												
												jQuery(globaloptions.selectorModalboxBodyContentContainer, globaloptions.selectorModalboxContainer).html(
													data
												);
												
												centerModalBox({
													callFunctionAfterShow : globaloptions.callFunctionAfterShow
												});
												
											});
											
										},
										error : function(XMLHttpRequest, textStatus, errorThrown){
											ajaxRedirect({ 
												ar_XMLHttpRequest	: XMLHttpRequest,
												ar_textStatus		: textStatus,
												ar_errorThrown		: errorThrown,
												targetContainer		: globaloptions.selectorModalboxContainer + " " + globaloptions.selectorModalboxBodyContentContainer
											});
										}
									});
									
									break;
									
								}
							}
						}
						
						showFadingLayer({
							callFunctionAfterShow : getCurrentContent
						});
						
					}
				}
			}
			/************ openModalBox - END ************/
			
			
			
			/************ showFadingLayer - BEGIN ************/
			function showFadingLayer(settings){
				
				
				var settings = jQuery.extend({//defaults
					isResized : false,
					callFunctionAfterShow : null
				}, settings || {} );
				
				
				if ( jQuery(globaloptions.selectorFadingLayer).length == 0 ) {
					
					/* append fading container first - BEGIN */
					if( globaloptions.setTypeOfFadingLayer == "white" ){
						var setStyleOfFadingLayer = globaloptions.setStylesOfFadingLayer["white"];
					} else if ( globaloptions.setTypeOfFadingLayer == "black" ){
						var setStyleOfFadingLayer = globaloptions.setStylesOfFadingLayer["black"];
					} else if ( globaloptions.setTypeOfFadingLayer == "custom" && globaloptions.setStylesOfFadingLayer["custom"] ){
						var setStyleOfFadingLayer = globaloptions.setStylesOfFadingLayer["custom"];
					} else {//globaloptions.setTypeOfFadingLayer == "disable"
						var setStyleOfFadingLayer = globaloptions.setStylesOfFadingLayer["transparent"];
					}
					
					var prepareNameOfFadingLayer = methods.cleanupSelectorName({
						replaceValue : globaloptions.selectorFadingLayer
					});
					
					jQuery("body").append('<div id="' + prepareNameOfFadingLayer + '" style="' + setStyleOfFadingLayer + '"></div>');
					/* append fading container first - END */
					
					
					/* getGeneratedFaderObj - BEGIN */
					var getGeneratedFaderObj = jQuery(globaloptions.selectorFadingLayer);
					
					if( globaloptions.setTypeOfFadingLayer == "disable" ){
						globaloptions.effectType_show_fadingLayer[0] = ""; // reset to default
					}
					
					switch( globaloptions.effectType_show_fadingLayer[0] ){
						
						case 'fade' : {
							
							getGeneratedFaderObj.fadeIn( globaloptions.effectType_show_fadingLayer[1], function(){
								centerModalBox({
									isResized : settings.isResized,
									callFunctionAfterShow : settings.callFunctionAfterShow
								});
							});
							
							break;
							
						} default : {
							
							getGeneratedFaderObj.show();
						
							centerModalBox({
								isResized : settings.isResized,
								callFunctionAfterShow : settings.callFunctionAfterShow
							});
							
							break;
						}
					};
					
					
					jQuery(window).resize(function(){
						if ( getGeneratedFaderObj.is(':visible') ) {
							centerModalBox({
								isResized : true
							});
						}
					});
					/* getGeneratedFaderObj - END */
					
				} else {
					
					centerModalBox({
						isResized : settings.isResized,
						callFunctionAfterShow : settings.callFunctionAfterShow
					});
					
				}
			}
			/************ showFadingLayer - END ************/
			
			
			
			/************ centerModalBox - BEGIN ************/
			function centerModalBox(settings){
			
			
				var settings = jQuery.extend({
					isResized : false,
					callFunctionAfterShow : null
				}, settings || {} );
				
				
				var modalboxContainerObj = jQuery(globaloptions.selectorModalboxContainer);
				
				
				if( jQuery(globaloptions.selectorPreCacheContainer).length == 0 && modalboxContainerObj.length != 0 ){
					
					
					if( jQuery("body a.modalBoxTopLink").length == 0 ){
						jQuery("body").prepend('<a class="modalBoxTopLink"></a>');
					}
					
					
					// default settings
					var scrollToTop = false;
					var positionAttr = 'absolute';
					var setPositionTop = 0;
					var getModalboxContainerWidth = modalboxContainerObj.width();
					var getModalboxContainerHeight = modalboxContainerObj.height();
					
					
					/*~~~ setPositionLeft / BEGIN ~~~*/
					var setPositionLeft = parseInt( jQuery(window).width() - getModalboxContainerWidth ) / 2;
					if( setPositionLeft <= 0 ){
						setPositionLeft = 0;
					}
					
					if( globaloptions.positionLeft ){
						setPositionLeft = globaloptions.positionLeft + 'px';
					} else {
						setPositionLeft = setPositionLeft + 'px';
					}
					/*~~~ setPositionLeft / END ~~~*/
					
					
					/*~~~ setPositionTop / BEGIN ~~~*/
					if( globaloptions.positionTop ){
						
						setPositionTop = parseInt( 
							jQuery(window).height() - getModalboxContainerHeight
						);
						
						if( setPositionTop > parseInt( globaloptions.positionTop ) ){
							positionAttr = 'fixed';
						}
						
						setPositionTop = globaloptions.positionTop + 'px';
					
					} else {
						
						setPositionTop = parseInt( jQuery(window).height() - getModalboxContainerHeight - 70 ) / 2;
						
						if( setPositionTop <= 0 ){
						
							setPositionTop = globaloptions.minimalTopSpacingOfModalbox + 'px';
							scrollToTop = true;
							
						} else {
							
							setPositionTop = setPositionTop + 'px';
							positionAttr = 'fixed';
						}
					}
					/*~~~ setPositionTop / END ~~~*/
					
					
					
					/*~~~ initLastSteps / BEGIN ~~~*/
					function initLastSteps(){
						
						if( scrollToTop && !modalboxContainerObj.hasClass("modalboxScrollingSuccessfully") ){
							modalboxContainerObj.addClass("modalboxScrollingSuccessfully");
							methods.scrollTo();
						}
						
						if( !settings.isResized ){
							
							if( settings.callFunctionAfterShow ){
								settings.callFunctionAfterShow();
							}
							
							if( globaloptions.usejqueryuidragable ){
								modalboxContainerObj.draggable("destroy").draggable({ 
									opacity: false, 
									iframeFix: true, 
									refreshPositions: true 
								});
							}
							
						}
					}
					/*~~~ initLastSteps / END ~~~*/
					
					
					
					/*~~~ initPositioning / BEGIN ~~~*/
					switch( globaloptions.effectType_show_modalBox[0] ){
						
						case 'fade' : {
							
							if( modalboxContainerObj.hasClass("modalboxFadingSuccessfully") ){
							
								modalboxContainerObj.css({
									position	: positionAttr,
									left		: setPositionLeft,
									top			: setPositionTop,
									display		: "block",
									visibility	: "visible"
								});
								
								initLastSteps();
								
							} else {
								
								// classic fadeIn - problems with transparency in ie browsers
								modalboxContainerObj.css({
									
									position	: positionAttr,
									left		: setPositionLeft,
									top			: setPositionTop,
									visibility	: "visible"
									
								}).fadeIn( globaloptions.effectType_show_modalBox[1] , function(){
								
									jQuery(this).addClass("modalboxFadingSuccessfully");
									
									initLastSteps();
									
								});
							}
							
							break;
							
						} default : {
							
							modalboxContainerObj.css({
								position	: positionAttr,
								left		: setPositionLeft,
								top			: setPositionTop,
								display		: "block",
								visibility	: "visible"
							});
							
							initLastSteps();
							
							break;
						}
					};
					/*~~~ initPositioning / END ~~~*/
					
					
				}
			}
			/************ centerModalBox - END ************/
			
			
		},
		/********** init - END **********/
		
		
		
		/********** close - BEGIN **********/
		close : function(settings){
			
			
			// merge the plugin defaults with custom settings
			var settings = jQuery.extend({}, defaults, settings);
			
			
			if( settings.selectorFadingLayer && settings.selectorModalboxContainer ){
			
				settings.callFunctionBeforeHide();
				
				var containerObj = jQuery(settings.selectorFadingLayer + ', ' + settings.selectorModalboxContainer);
				
				if( settings.setTypeOfFadingLayer == "disable" ){
					settings.effectType_hide_fadingLayer[0] = ""; // reset to default
				}
				
				switch ( settings.effectType_hide_fadingLayer[0] ){
					case 'fade' : {
						
						switch ( settings.effectType_hide_modalBox[0] ){
							case 'fade' : {
								
								jQuery(settings.selectorModalboxContainer).fadeOut( settings.effectType_hide_modalBox[1], function(){
									jQuery(settings.selectorFadingLayer).fadeOut( settings.effectType_hide_fadingLayer[1], function(){
										removeLayer( containerObj );
									});
								});
								
								break;
								
							} default : {
								
								jQuery(settings.selectorModalboxContainer).hide();
								
								jQuery(settings.selectorFadingLayer).fadeOut( settings.effectType_hide_fadingLayer[1], function(){
									removeLayer( containerObj );
								});
								
								break;
							}
						};
						
						break;
						
					} default : {
						
						switch ( settings.effectType_hide_modalBox[0] ){
							case 'fade' : {
								
								jQuery(settings.selectorModalboxContainer).fadeOut( settings.effectType_hide_modalBox[1], function(){
									removeLayer( containerObj );
								});
								
								break;
								
							} default : {
								
								removeLayer( containerObj );
								
								break;
							}
						};
						
						break;
					}
				};
				
			}
			
			
			function removeLayer(container){
				
				container.remove();
				
				settings.callFunctionAfterHide();
			}
			
		},
		/********** close - END **********/
		
		
		
		/********** clean - BEGIN **********/
		clean : function(settings){
			
			var settings = jQuery.extend({
				setModalboxContentContainer	: null,
				selectorAjaxLoader : null,
				localizedStrings : null
			}, settings || {} );
			
			if( settings.setModalboxContentContainer ){
				jQuery(settings.setModalboxContentContainer).html('<div id="' + settings.selectorAjaxLoader + '">' + settings.localizedStrings + '</div>');
			}
			
		},
		/********** clean - END **********/
		
		
		
		/************ scrollTo - BEGIN ************/
		scrollTo : function(settings){
			
			/*
				Example:
				-----------------------------
				methods.scrollTo({
					targetElement : "#footer"
				});
			*/
			
			var settings = jQuery.extend({// default settings
				targetElement	: "a.modalBoxTopLink",
				typeOfAnimation	: 'swing', // options: linear, swing, easing
				animationSpeed	: 800,
				callAfterSuccess : function(){}
			}, settings || {} );
			
			
			if( settings.targetElement ){
				
				if( jQuery.browser.webkit ){
					var animateObj = jQuery("body");
				} else {
					var animateObj = jQuery("html");
				}
				
				animateObj.animate({ 
					scrollTop : jQuery(settings.targetElement).offset().top 
				}, settings.animationSpeed, settings.typeOfAnimation, function(){
					// Animation complete.
					settings.callAfterSuccess();
				});
				
			}
		},
		/************ scrollTo - END ************/
		
		
		
		/********** cleanupSelectorName - BEGIN **********/
		cleanupSelectorName : function(settings){
			
			var settings = jQuery.extend({
				replaceValue : ''
			}, settings || {} );
			
			var currentReturnValue 	= settings.replaceValue;
			currentReturnValue 		= currentReturnValue.replace(/[#]/g, "");
			currentReturnValue 		= currentReturnValue.replace(/[.]/g, "");
			
			return currentReturnValue;
			
		},
		/********** cleanupSelectorName - END **********/
		
		
		
		/********** precache - BEGIN **********/
		precache : function(settings){
			
			// merge the plugin defaults with custom settings
			var settings = jQuery.extend({}, defaults, settings);
			
			if( settings.selectorPreCacheContainer ){
				if( jQuery(settings.selectorPreCacheContainer).length == 0 ){
					
					var prepareNameOfPreCacheContainer = methods.cleanupSelectorName({
						replaceValue : settings.selectorPreCacheContainer
					});
					
					var createModalboxContainer = methods.modalboxBuilder();
					
					var preCacheContainer = '';
					preCacheContainer += '<div id="' + prepareNameOfPreCacheContainer + '" style="position:absolute; left:-9999px; top:-9999px;">';
						preCacheContainer += createModalboxContainer;
					preCacheContainer += '</div>';
					
					jQuery("body").append(preCacheContainer);
					
					jQuery(settings.selectorModalboxContainer).show();
				}
			}
			
		},
		/********** precache - END **********/
		
		
		
		/********** modalboxBuilder - BEGIN **********/
		modalboxBuilder : function(settings){
			
			var settings = jQuery.extend({
				customStyles : ''
			}, settings || {} );
		
			
			// merge the plugin defaults with custom options
			settings = jQuery.extend({}, defaults, settings);
			
			
			var prepareNameOfModalboxContainer = methods.cleanupSelectorName({
				replaceValue : settings.selectorModalboxContainer
			});
			
			var prepareNameOfModalboxBodyContainer = methods.cleanupSelectorName({
				replaceValue : settings.selectorModalboxBodyContainer
			});
			
			var prepareNameOfModalboxContentContainer = methods.cleanupSelectorName({
				replaceValue : settings.selectorModalboxBodyContentContainer
			});
			
			var prepareNameOfCloseButtonContainer = methods.cleanupSelectorName({
				replaceValue : settings.selectorModalboxCloseContainer
			});
			
			var prepareNameOfAjaxLoader = methods.cleanupSelectorName({
				replaceValue : settings.selectorAjaxLoader
			});
			
			
			var createModalboxContainer = '';
			createModalboxContainer += '<div id="' + prepareNameOfModalboxContainer + '"' + settings.customStyles + '>';
				createModalboxContainer += '<div id="' + prepareNameOfModalboxBodyContainer + '">';
					createModalboxContainer += settings.setModalboxLayoutContainer_Begin;
						
						createModalboxContainer += '<div class="' + prepareNameOfModalboxContentContainer + '">';
							createModalboxContainer += '<div id="' + prepareNameOfAjaxLoader + '">' + settings.localizedStrings["messageAjaxLoader"] + '</div>';
						createModalboxContainer += '</div>';
						
					createModalboxContainer += settings.setModalboxLayoutContainer_End;
					createModalboxContainer += '<div id="' + prepareNameOfCloseButtonContainer + '"><a href="javascript:void(0);" class="closeModalBox"><span class="closeModalBox">' + settings.localizedStrings["messageCloseWindow"] + '</span></a></div>';
				createModalboxContainer += '</div>';
			createModalboxContainer += '</div>';
			
			return createModalboxContainer;
			
		}
		/********** modalboxBuilder - END **********/
	};
	
	
	jQuery.fn.modalBox = function( method ) {
		// Method calling logic
		if ( methods[method] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			jQuery.error( 'Method ' +	method + ' does not exist on jQuery.modalBox' );
		}		
	};
	
	
	jQuery(document).ready(function(){//default Initializing
		jQuery.fn.modalBox("precache");
		jQuery(".openmodalbox").modalBox();
	});
	
	
})(jQuery);