!function(e){function t(t){for(var n,i,s=t[0],c=t[1],u=t[2],f=0,p=[];f<s.length;f++)i=s[f],Object.prototype.hasOwnProperty.call(a,i)&&a[i]&&p.push(a[i][0]),a[i]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(e[n]=c[n]);for(l&&l(t);p.length;)p.shift()();return o.push.apply(o,u||[]),r()}function r(){for(var e,t=0;t<o.length;t++){for(var r=o[t],n=!0,s=1;s<r.length;s++){var c=r[s];0!==a[c]&&(n=!1)}n&&(o.splice(t--,1),e=i(i.s=r[0]))}return e}var n={},a={3:0},o=[];function i(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,i),r.l=!0,r.exports}i.m=e,i.c=n,i.d=function(e,t,r){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(i.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)i.d(r,n,function(t){return e[t]}.bind(null,n));return r},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="";var s=window.webpackJsonp=window.webpackJsonp||[],c=s.push.bind(s);s.push=t,s=s.slice();for(var u=0;u<s.length;u++)t(s[u]);var l=c;o.push([438,0]),r()}({10:
/*!****************************************************!*\
  !*** ./src/react-extension/contexts/AppContext.js ***!
  \****************************************************/
/*! exports provided: withAppContext, default */
/*! exports used: default, withAppContext */function(e,t,r){"use strict";r.d(t,"b",(function(){return E}));var n=r(/*! @babel/runtime/helpers/extends */12),a=r.n(n),o=r(/*! @babel/runtime/helpers/classCallCheck */3),i=r.n(o),s=r(/*! @babel/runtime/helpers/createClass */6),c=r.n(s),u=r(/*! @babel/runtime/helpers/inherits */7),l=r.n(u),f=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),p=r.n(f),h=r(/*! @babel/runtime/helpers/getPrototypeOf */2),d=r.n(h),y=r(/*! react */0),v=r.n(y);function m(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=d()(e);if(t){var a=d()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return p()(this,r)}}var b=v.a.createContext({user:null,users:null,roles:null,rememberMeOptions:{},resources:null,resource:null,shareResources:null,selectedResources:null,selectedUser:null,folders:null,resourceCommentId:null,mustRefreshComments:!1,siteSettings:null,userSettings:null});function E(e){return function(t){l()(n,t);var r=m(n);function n(){return i()(this,n),r.apply(this,arguments)}return c()(n,[{key:"render",value:function(){var t=this;return v.a.createElement(b.Consumer,null,(function(r){return v.a.createElement(e,a()({context:r},t.props))}))}}]),n}(v.a.Component)}t.a=b},13:
/*!*****************************************************!*\
  !*** ./src/react/lib/Common/Object/getPropValue.js ***!
  \*****************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";t.a=function(e,t){return t.split(".").reduce((function(e,t){return void 0===e?e:e[t]}),e)}},14:
/*!********************************************************************!*\
  !*** ./src/react-extension/lib/apiClient/apiClient.js + 2 modules ***!
  \********************************************************************/
/*! exports provided: ApiClient */
/*! exports used: ApiClient */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/asyncToGenerator.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/classCallCheck.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/createClass.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/defineProperty.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/getPrototypeOf.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/inherits.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/slicedToArray.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/wrapNativeSuper.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/regenerator/index.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/lib/Error/passboltApiFetchError.js because of ./src/react-extension/contexts/ApiSetupContext.js */function(e,t,r){"use strict";r.d(t,"a",(function(){return N}));var n=r(46),a=r.n(n),o=r(1),i=r.n(o),s=r(5),c=r.n(s),u=r(16),l=r.n(u),f=r(3),p=r.n(f),h=r(6),d=r.n(h),y=r(24),v=r(7),m=r.n(v),b=r(8),E=r.n(b),R=r(2),g=r.n(R),w=r(15),k=r.n(w);function O(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=g()(e);if(t){var a=g()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return E()(this,r)}}var T=function(e){m()(r,e);var t=O(r);function r(){var e;return p()(this,r),(e=t.call(this,"An internal error occurred. The server response could not be parsed. Please contact your administrator.")).name="PassboltBadResponseError",e}return r}(k()(Error));function S(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=g()(e);if(t){var a=g()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return E()(this,r)}}var x=function(e){m()(r,e);var t=S(r);function r(e){var n;return p()(this,r),e=e||"The service is unavailable",(n=t.call(this,e)).name="PassboltServiceUnavailableError",n}return r}(k()(Error));function A(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function C(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?A(Object(r),!0).forEach((function(t){l()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):A(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 */var N=function(){function e(t){if(p()(this,e),this.options=t,!this.options.getBaseUrl())throw new TypeError("ApiClient constructor error: baseUrl is required.");if(!this.options.getResourceName())throw new TypeError("ApiClient constructor error: resourceName is required.");try{var r=this.options.getBaseUrl().toString();r.endsWith("/")&&(r=r.slice(0,-1)),this.baseUrl="".concat(r,"/").concat(this.options.getResourceName()),this.baseUrl=new URL(this.baseUrl)}catch(e){throw new TypeError("ApiClient constructor error: b.")}this.apiVersion="api-version=v2"}var t,r,n,o,s,u;return d()(e,[{key:"getDefaultHeaders",value:function(){return{Accept:"application/json","content-type":"application/json"}}},{key:"buildFetchOptions",value:function(){return{credentials:"include",headers:C(C({},this.getDefaultHeaders()),this.options.getHeaders())}}},{key:"get",value:(u=c()(i.a.mark((function e(t,r){var n;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return this.assertValidId(t),n=this.buildUrl("".concat(this.baseUrl,"/").concat(t),r||{}),e.abrupt("return",this.fetchAndHandleResponse("GET",n));case 3:case"end":return e.stop()}}),e,this)}))),function(e,t){return u.apply(this,arguments)})},{key:"delete",value:(s=c()(i.a.mark((function e(t,r,n,a){var o,s;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return this.assertValidId(t),void 0===a&&(a=!1),o=a?this.buildUrl("".concat(this.baseUrl,"/").concat(t,"/dry-run"),n||{}):this.buildUrl("".concat(this.baseUrl,"/").concat(t),n||{}),s=null,r&&(s=this.buildBody(r)),e.abrupt("return",this.fetchAndHandleResponse("DELETE",o,s));case 6:case"end":return e.stop()}}),e,this)}))),function(e,t,r,n){return s.apply(this,arguments)})},{key:"findAll",value:(o=c()(i.a.mark((function e(t){var r;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=this.buildUrl(this.baseUrl.toString(),t||{}),e.next=3,this.fetchAndHandleResponse("GET",r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e,this)}))),function(e){return o.apply(this,arguments)})},{key:"create",value:(n=c()(i.a.mark((function e(t,r){var n,a;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=this.buildUrl(this.baseUrl.toString(),r||{}),a=this.buildBody(t),e.abrupt("return",this.fetchAndHandleResponse("POST",n,a));case 3:case"end":return e.stop()}}),e,this)}))),function(e,t){return n.apply(this,arguments)})},{key:"update",value:(r=c()(i.a.mark((function e(t,r,n,a){var o,s;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return this.assertValidId(t),void 0===a&&(a=!1),o=a?this.buildUrl("".concat(this.baseUrl,"/").concat(t,"/dry-run"),n||{}):this.buildUrl("".concat(this.baseUrl,"/").concat(t),n||{}),s=null,r&&(s=this.buildBody(r)),e.abrupt("return",this.fetchAndHandleResponse("PUT",o,s));case 6:case"end":return e.stop()}}),e,this)}))),function(e,t,n,a){return r.apply(this,arguments)})},{key:"assertValidId",value:function(e){if(!e)throw new TypeError("ApiClient.assertValidId error: id cannot be empty");if("string"!=typeof e)throw new TypeError("ApiClient.assertValidId error: id should be a string")}},{key:"assertMethod",value:function(e){"string"!=typeof e&&new TypeError("ApiClient.assertValidMethod method should be a string.");["GET","POST","PUT","DELETE"].indexOf(e)<0&&new TypeError("ApiClient.assertValidMethod error: method ".concat(e," is not supported."))}},{key:"assertUrl",value:function(e){if(!e)throw new TypeError("ApliClient.assertUrl error: url is required.");if(!(e instanceof URL))throw new TypeError("ApliClient.assertUrl error: url should be a valid URL object.")}},{key:"assertBody",value:function(e){"string"!=typeof e&&new TypeError("ApiClient.assertBody error: body should be a string.")}},{key:"buildBody",value:function(e){return JSON.stringify(e)}},{key:"buildUrl",value:function(e,t){if("string"!=typeof e)throw new TypeError("ApiClient.buildUrl error: url should be a string.");var r=new URL("".concat(e,".json?").concat(this.apiVersion));t=t||{};for(var n=function(){var e=a()(i[o],2),t=e[0],n=e[1];if("string"!=typeof t)throw new TypeError("ApiClient.buildUrl error: urlOptions key should be a string.");if("string"==typeof n)r.searchParams.append(t,n);else{if(!Array.isArray(n))throw new TypeError("ApiClient.buildUrl error: urlOptions value should be a string or array.");n.forEach((function(e){r.searchParams.append(t,e)}))}},o=0,i=Object.entries(t);o<i.length;o++)n();return r}},{key:"fetchAndHandleResponse",value:(t=c()(i.a.mark((function e(t,r,n,a){var o,s,c,u;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return this.assertUrl(r),this.assertMethod(t),n&&this.assertBody(n),(c=C(C({},this.buildFetchOptions()),a)).method=t,n&&(c.body=n),e.prev=6,e.next=9,fetch(r.toString(),c);case 9:o=e.sent,e.next=15;break;case 12:throw e.prev=12,e.t0=e.catch(6),new x(e.t0.message);case 15:return e.prev=15,e.next=18,o.json();case 18:s=e.sent,e.next=24;break;case 21:throw e.prev=21,e.t1=e.catch(15),new T;case 24:if(o.ok){e.next=27;break}throw u=s.header.message,new y.a(u,{code:o.status,body:s.body});case 27:return e.abrupt("return",s);case 28:case"end":return e.stop()}}),e,this,[[6,12],[15,21]])}))),function(e,r,n,a){return t.apply(this,arguments)})}]),e}()},19:
/*!**************************************************************!*\
  !*** ./src/react-extension/lib/Browser/detectBrowserName.js ***!
  \**************************************************************/
/*! exports provided: BROWSER_NAMES, detectBrowserName */
/*! exports used: BROWSER_NAMES, detectBrowserName */function(e,t,r){"use strict";r.d(t,"a",(function(){return n})),r.d(t,"b",(function(){return a}));
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */
var n={CHROME:"chrome",EDGE:"edge",FIREFOX:"firefox",INTERNET_EXPLORER:"internet-explorer",OPERA:"opera",SAFARI:"safari",SAMSUNG:"samsung",UNKNOWN:"unknown"};function a(){var e=window.navigator.userAgent.toLowerCase();return e.indexOf("firefox")>-1?n.FIREFOX:e.indexOf("samsungbrowser")>-1?n.SAMSUNG:e.indexOf("opera")>-1||e.indexOf("opr")>-1?n.OPERA:e.indexOf("trident")>-1?n.INTERNET_EXPLORER:e.indexOf("edge")>-1?n.EDGE:e.indexOf("chrome")>-1?n.CHROME:e.indexOf("safari")>-1?n.SAFARI:n.UNKNOWN}},24:
/*!****************************************************************!*\
  !*** ./src/react-extension/lib/Error/passboltApiFetchError.js ***!
  \****************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/inherits */7),i=r.n(o),s=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),c=r.n(s),u=r(/*! @babel/runtime/helpers/getPrototypeOf */2),l=r.n(u),f=r(/*! @babel/runtime/helpers/wrapNativeSuper */15);function p(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=l()(e);if(t){var a=l()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return c()(this,r)}}var h=function(e){i()(r,e);var t=p(r);function r(e,n){var o;return a()(this,r),(o=t.call(this,e)).name="PassboltApiFetchError",o.data=n||{},o}return r}(r.n(f)()(Error));t.a=h},31:
/*!***************************************************************************!*\
  !*** ./src/react-extension/lib/apiClient/apiClientOptions.js + 1 modules ***!
  \***************************************************************************/
/*! exports provided: ApiClientOptions */
/*! exports used: ApiClientOptions */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/classCallCheck.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/createClass.js (<- Module is not an ECMAScript module) */function(e,t,r){"use strict";r.d(t,"a",(function(){return c}));var n=r(3),a=r.n(n),o=r(6),i=r.n(o),s=function(){function e(t){a()(this,e),this.setToken(t)}return i()(e,[{key:"setToken",value:function(e){this.validate(e),this.token=e}},{key:"validate",value:function(e){if(!e)throw new TypeError("CSRF token cannot be empty.");if("string"!=typeof e)throw new TypeError("CSRF token should be a string.")}},{key:"toFetchHeaders",value:function(){return{"X-CSRF-Token":this.token}}}]),e}(),c=function(){function e(){a()(this,e)}return i()(e,[{key:"setBaseUrl",value:function(e){if(!e)throw new TypeError("ApiClientOption baseUrl is required.");if("string"==typeof e)try{this.baseUrl=new URL(e)}catch(e){throw new TypeError("ApiClientOption baseUrl is invalid.")}else{if(!(e instanceof URL))throw new TypeError("ApiClientOptions baseurl should be a string or URL");this.baseUrl=e}return this}},{key:"setCsrfToken",value:function(e){if(!e)throw new TypeError("ApiClientOption csrfToken is required.");if("string"==typeof e)this.csrfToken=new s(e);else{if(!(e instanceof s))throw new TypeError("ApiClientOption csrfToken should be a string or a valid CsrfToken.");this.csrfToken=e}return this}},{key:"setResourceName",value:function(e){if(!e)throw new TypeError("ApiClientOptions.setResourceName resourceName is required.");if("string"!=typeof e)throw new TypeError("ApiClientOptions.setResourceName resourceName should be a valid string.");return this.resourceName=e,this}},{key:"getBaseUrl",value:function(){return this.baseUrl}},{key:"getResourceName",value:function(){return this.resourceName}},{key:"getHeaders",value:function(){if(this.csrfToken)return this.csrfToken.toFetchHeaders()}}]),e}()},42:
/*!******************************************************************************!*\
  !*** ./src/react/components/Common/Loading/LoadingSpinner/LoadingSpinner.js ***!
  \******************************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/createClass */6),i=r.n(o),s=r(/*! @babel/runtime/helpers/inherits */7),c=r.n(s),u=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),l=r.n(u),f=r(/*! @babel/runtime/helpers/getPrototypeOf */2),p=r.n(f),h=r(/*! react */0),d=r.n(h),y=r(/*! prop-types */4),v=r.n(y);function m(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 */();return function(){var r,n=p()(e);if(t){var a=p()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return l()(this,r)}}var b=function(e){c()(r,e);var t=m(r);function r(){return a()(this,r),t.apply(this,arguments)}return i()(r,[{key:"render",value:function(){return d.a.createElement(d.a.Fragment,null,d.a.createElement("div",{className:"processing-wrapper"},d.a.createElement("div",{className:"processing-text"},this.props.title&&d.a.createElement("h2",null,this.props.title),d.a.createElement("h2",null,"Please wait..."))))}}]),r}(d.a.Component);b.propTypes={title:v.a.string},t.a=b},43:
/*!************************************************************************************!*\
  !*** ./src/react-extension/components/Authentication/CheckMailBox/CheckMailBox.js ***!
  \************************************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/createClass */6),i=r.n(o),s=r(/*! @babel/runtime/helpers/inherits */7),c=r.n(s),u=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),l=r.n(u),f=r(/*! @babel/runtime/helpers/getPrototypeOf */2),p=r.n(f),h=r(/*! react */0),d=r.n(h);function y(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=p()(e);if(t){var a=p()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return l()(this,r)}}var v=function(e){c()(r,e);var t=y(r);function r(){return a()(this,r),t.apply(this,arguments)}return i()(r,[{key:"render",value:function(){return d.a.createElement("div",{className:"email-sent-instructions"},d.a.createElement("div",{className:"email-sent-bg"}),d.a.createElement("h1",null,"Check your mailbox!"),d.a.createElement("p",null,"We send you a link to verify your email.",d.a.createElement("br",null),"Check your spam folder if you do not see hear from us after a while."))}}]),r}(h.Component);t.a=v},438:
/*!***********************************************************!*\
  !*** ./src/react-extension/ApiSetup.entry.js + 3 modules ***!
  \***********************************************************/
/*! no exports provided */
/*! all exports used */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/asyncToGenerator.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/classCallCheck.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/createClass.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/extends.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/getPrototypeOf.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/inherits.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/@babel/runtime/regenerator/index.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/components/Authentication/CheckMailBox/CheckMailBox.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/components/Authentication/DisplayBrowserNotSupported/DisplayBrowserNotSupported.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/components/Authentication/DisplayError/DisplayError.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/components/Authentication/InstallExtension/InstallExtension.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/contexts/AppContext.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/lib/Browser/detectBrowserName.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/lib/Error/passboltApiFetchError.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/lib/apiClient/apiClient.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react-extension/lib/apiClient/apiClientOptions.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react/components/Common/Loading/LoadingSpinner/LoadingSpinner.js */
/*! ModuleConcatenation bailout: Cannot concat with ./src/react/lib/Common/Object/getPropValue.js */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/prop-types/index.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/react-dom/index.js (<- Module is not an ECMAScript module) */
/*! ModuleConcatenation bailout: Cannot concat with ./node_modules/react/index.js (<- Module is not an ECMAScript module) */function(e,t,r){"use strict";r.r(t);var n=r(0),a=r.n(n),o=r(45),i=r.n(o),s=r(3),c=r.n(s),u=r(6),l=r.n(u),f=r(7),p=r.n(f),h=r(8),d=r.n(h),y=r(2),v=r.n(y),m=r(10),b=r(12),E=r.n(b),R=r(1),g=r.n(R),w=r(5),k=r.n(w),O=r(4),T=r.n(O),S=r(14),x=r(19),A=r(24),C=r(13);function N(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=v()(e);if(t){var a=v()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return d()(this,r)}}var U=a.a.createContext({userId:null,token:null,state:null,onInitializeSetupRequested:function(){}}),P=function(e){p()(o,e);var t,r,n=N(o);function o(e){var t;return c()(this,o),(t=n.call(this,e)).state=Object.assign(t.defaultState,e.value),t}return l()(o,[{key:"onInitializeSetupRequested",value:(r=k()(g.a.mark((function e(){return g.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(this.state.userId&&this.state.token){e.next=2;break}return e.abrupt("return",this.setState({state:I.ERROR_STATE}));case 2:if(this.isBrowserSupported()){e.next=4;break}return e.abrupt("return",this.setState({state:I.DOWNLOAD_SUPPORTED_BROWSER_STATE}));case 4:return e.next=6,this.verifySetupInfo().then(this.handleSetupVerifySuccess.bind(this)).catch(this.handleSetupVerifyError.bind(this));case 6:case"end":return e.stop()}}),e,this)}))),function(){return r.apply(this,arguments)})},{key:"handleSetupVerifySuccess",value:function(){this.setState({state:I.INSTALL_EXTENSION_STATE})}},{key:"handleSetupVerifyError",value:function(e){if(e instanceof A.a&&Object(C.a)(e,"data.body.token.expired"))return this.setState({state:I.CHECK_MAILBOX_STATE});return this.setState({state:I.ERROR_STATE})}},{key:"isBrowserSupported",value:function(){var e=Object(x.b)();return[x.a.CHROME,x.a.FIREFOX].includes(e)}},{key:"verifySetupInfo",value:(t=k()(g.a.mark((function e(){var t,r,n,a;return g.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(t=this.props.context.getApiClientOptions()).setResourceName("setup"),r=new S.a(t),e.next=5,r.get("install/".concat(this.state.userId,"/").concat(this.state.token));case 5:return n=e.sent,a=n.body,e.abrupt("return",a);case 8:case"end":return e.stop()}}),e,this)}))),function(){return t.apply(this,arguments)})},{key:"render",value:function(){return a.a.createElement(U.Provider,{value:this.state},this.props.children)}},{key:"defaultState",get:function(){return{userId:null,token:null,state:I.INITIAL_STATE,onInitializeSetupRequested:this.onInitializeSetupRequested.bind(this)}}}]),o}(a.a.Component);P.propTypes={context:T.a.any,value:T.a.any,children:T.a.any};var D=Object(m.b)(P);var I={INITIAL_STATE:"Initial State",INSTALL_EXTENSION_STATE:"Install extension",DOWNLOAD_SUPPORTED_BROWSER_STATE:"Download supported browser",CHECK_MAILBOX_STATE:"Check mailbox",ERROR_STATE:"Error"},_=r(31),j=r(42),F=r(61),L=r(62),B=r(43),M=r(44);function H(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=v()(e);if(t){var a=v()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return d()(this,r)}}var V=function(e){p()(r,e);var t=H(r);function r(){return c()(this,r),t.apply(this,arguments)}return l()(r,[{key:"componentDidMount",value:function(){this.initializeSetup()}},{key:"initializeSetup",value:function(){var e=this;setTimeout((function(){return e.props.apiSetupContext.onInitializeSetupRequested()}),1e3)}},{key:"render",value:function(){switch(this.props.apiSetupContext.state){case I.INSTALL_EXTENSION_STATE:return a.a.createElement(F.a,null);case I.DOWNLOAD_SUPPORTED_BROWSER_STATE:return a.a.createElement(L.a,null);case I.CHECK_MAILBOX_STATE:return a.a.createElement(B.a,null);case I.ERROR_STATE:return a.a.createElement(M.a,null);default:return a.a.createElement(j.a,null)}}}]),r}(n.Component);V.propTypes={apiSetupContext:T.a.object};var X,q=(X=V,function(e){p()(r,e);var t=N(r);function r(){return c()(this,r),t.apply(this,arguments)}return l()(r,[{key:"render",value:function(){var e=this;return a.a.createElement(U.Consumer,null,(function(t){return a.a.createElement(X,E()({apiSetupContext:t},e.props))}))}}]),r}(a.a.Component));function z(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=v()(e);if(t){var a=v()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return d()(this,r)}}var W=function(e){p()(r,e);var t=z(r);function r(e){var n;return c()(this,r),(n=t.call(this,e)).state=n.defaultState,n.userId=null,n.token=null,n.initializeProperties(),n}return l()(r,[{key:"initializeProperties",value:function(){var e="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}",t="setup/install/(".concat(e,")/(").concat(e,")$"),r=new RegExp(t),n=window.location.pathname.match(r);n?(this.userId=n[1],this.token=n[2]):console.error("Unable to retrieve the user id and token from the url")}},{key:"getApiClientOptions",value:function(){return(new _.a).setBaseUrl(this.state.trustedDomain)}},{key:"render",value:function(){return a.a.createElement(m.a.Provider,{value:this.state},a.a.createElement("div",{id:"container",className:"container page login"},a.a.createElement("div",{className:"content"},a.a.createElement("div",{className:"header"},a.a.createElement("div",{className:"logo"},a.a.createElement("span",{className:"visually-hidden"},"Passbolt"))),a.a.createElement("div",{className:"login-form"},a.a.createElement(D,{value:{userId:this.userId,token:this.token}},a.a.createElement(q,null))))))}},{key:"defaultState",get:function(){return{trustedDomain:this.baseUrl,getApiClientOptions:this.getApiClientOptions.bind(this)}}},{key:"baseUrl",get:function(){var e=document.getElementsByTagName("base")&&document.getElementsByTagName("base")[0];return e?e.attributes.href.value.replace(/\/*$/g,""):(console.error("Unable to retrieve the page base tag"),"")}}]),r}(n.Component),G=document.createElement("div");document.body.appendChild(G),i.a.render(a.a.createElement(W,null),G)},44:
/*!************************************************************************************!*\
  !*** ./src/react-extension/components/Authentication/DisplayError/DisplayError.js ***!
  \************************************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/createClass */6),i=r.n(o),s=r(/*! @babel/runtime/helpers/inherits */7),c=r.n(s),u=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),l=r.n(u),f=r(/*! @babel/runtime/helpers/getPrototypeOf */2),p=r.n(f),h=r(/*! react */0),d=r.n(h),y=r(/*! ../../../contexts/AppContext */10),v=r(/*! prop-types */4),m=r.n(v);function b(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=p()(e);if(t){var a=p()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return l()(this,r)}}var E=function(e){c()(r,e);var t=b(r);function r(){return a()(this,r),t.apply(this,arguments)}return i()(r,[{key:"render",value:function(){return d.a.createElement("div",{className:"setup-error"},d.a.createElement("h1",null,"Access to this service requires an invitation."),d.a.createElement("p",null,"This email is not associated with any approved users on this domain. Please contact your administrator to request an invitation link."),d.a.createElement("div",{className:"form-actions"},d.a.createElement("a",{href:"".concat(this.props.context.trustedDomain,"/users/recover"),className:"button primary big",role:"button"},"Try with another email")))}}]),r}(h.Component);E.propTypes={context:m.a.any},t.a=Object(y.b)(E)},61:
/*!********************************************************************************************!*\
  !*** ./src/react-extension/components/Authentication/InstallExtension/InstallExtension.js ***!
  \********************************************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/createClass */6),i=r.n(o),s=r(/*! @babel/runtime/helpers/inherits */7),c=r.n(s),u=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),l=r.n(u),f=r(/*! @babel/runtime/helpers/getPrototypeOf */2),p=r.n(f),h=r(/*! react */0),d=r.n(h),y=r(/*! ../../../lib/Browser/detectBrowserName */19),v=r(/*! ../../../contexts/AppContext */10),m=r(/*! prop-types */4),b=r.n(m);function E(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=p()(e);if(t){var a=p()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return l()(this,r)}}var R=function(e){c()(r,e);var t=E(r);function r(e){var n;return a()(this,r),(n=t.call(this,e)).state=n.getDefaultState(),n.bindCallbacks(),n}return i()(r,[{key:"getDefaultState",value:function(){return{browserName:Object(y.b)()}}},{key:"bindCallbacks",value:function(){this.handleRefreshClick=this.handleRefreshClick.bind(this)}},{key:"handleRefreshClick",value:function(){window.location.reload()}},{key:"render",value:function(){return d.a.createElement("div",{className:"install-extension"},d.a.createElement("h1",null,"Please install the browser extension."),d.a.createElement("p",null,"Please download the browser extension and refresh this page to continue."),this.state.browserName&&d.a.createElement("a",{href:this.storeUrl,className:this.storeClassName,target:"_blank",rel:"noopener noreferrer"},d.a.createElement("img",{src:this.browserStoreThumbnailUrl})),d.a.createElement("div",{className:"form-actions"},d.a.createElement("a",{href:this.storeUrl,className:"button primary big",role:"button",target:"_blank",rel:"noopener noreferrer"},"Download extension"),d.a.createElement("a",{onClick:this.handleRefreshClick,role:"button"},"Refresh to detect extension")))}},{key:"browserStoreThumbnailUrl",get:function(){switch(this.state.browserName){case y.a.CHROME:return"".concat(this.props.context.trustedDomain,"/img/third_party/ChromeWebStore_black.png");case y.a.FIREFOX:return"".concat(this.props.context.trustedDomain,"/img/third_party/FirefoxAMO_black.svg");default:return"".concat(this.props.context.trustedDomain,"/img/third_party/ChromeWebStore_black.png")}}},{key:"storeUrl",get:function(){switch(this.state.browserName){case y.a.CHROME:return"https://chrome.google.com/webstore/detail/passbolt-extension/didegimhafipceonhjepacocaffmoppf";case y.a.FIREFOX:return"https://addons.mozilla.org/fr/firefox/addon/passbolt";default:return"https://chrome.google.com/webstore/detail/passbolt-extension/didegimhafipceonhjepacocaffmoppf"}}},{key:"storeClassName",get:function(){return"browser-webstore ".concat(this.state.browserName)}}]),r}(h.Component);R.propTypes={context:b.a.any},t.a=Object(v.b)(R)},62:
/*!****************************************************************************************************************!*\
  !*** ./src/react-extension/components/Authentication/DisplayBrowserNotSupported/DisplayBrowserNotSupported.js ***!
  \****************************************************************************************************************/
/*! exports provided: default */
/*! exports used: default */function(e,t,r){"use strict";var n=r(/*! @babel/runtime/helpers/classCallCheck */3),a=r.n(n),o=r(/*! @babel/runtime/helpers/createClass */6),i=r.n(o),s=r(/*! @babel/runtime/helpers/inherits */7),c=r.n(s),u=r(/*! @babel/runtime/helpers/possibleConstructorReturn */8),l=r.n(u),f=r(/*! @babel/runtime/helpers/getPrototypeOf */2),p=r.n(f),h=r(/*! react */0),d=r.n(h),y=r(/*! prop-types */4),v=r.n(y),m=r(/*! ../../../contexts/AppContext */10);function b(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */();return function(){var r,n=p()(e);if(t){var a=p()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return l()(this,r)}}var E=function(e){c()(r,e);var t=b(r);function r(){return a()(this,r),t.apply(this,arguments)}return i()(r,[{key:"render",value:function(){return d.a.createElement("div",{className:"browser-not-supported"},d.a.createElement("h1",null,"Sorry, your browser is not supported."),d.a.createElement("p",null,"Please download chrome or firefox to get started with passbolt."),d.a.createElement("a",{href:"".concat("https://www.mozilla.org/firefox/download/thanks/"),className:"browser",target:"_blank",rel:"noopener noreferrer"},d.a.createElement("img",{src:"".concat(this.props.context.trustedDomain,"/img/third_party/firefox_logo.png")})),d.a.createElement("div",{className:"form-actions"},d.a.createElement("a",{href:"https://www.mozilla.org/firefox/download/thanks/",className:"button primary big",role:"button",target:"_blank",rel:"noopener noreferrer"},"Download firefox")))}}]),r}(h.Component);E.propTypes={context:v.a.any},t.a=Object(m.b)(E)}});