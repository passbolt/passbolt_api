steal(
	MAD_ROOT + '/core/singleton.js',
	MAD_ROOT + '/net/request.js'

).then(function ($) {

	/*
    * @class mad.net.Ajax
		* @inherits mad.core.Singleton
		* @see mad.net.Request
		* @see mad.net.Response
		* @see mad.net.ResponseHandler
		* @parent mad.net
		* 
		* <p>
		*		<h2>Simple Request Example</h2>
		*	
		*	@codestart
	mad.net.Ajax.request({
		'type': mad.net.Request.METHOD_POST,
		'url': APP_URL + '/resources/viewByCategory',
		'async': false,
		'dataType': 'passbolt.model.Resource.models',
		'success': function (request, response, body) {
			...
		},
		'error': function (request, response) {
			...
		}
	});
		*	@codeend
		*	
		*		<p>
		*			<b>dataType</b> The dataType options allow you to define the format of the server result.
		*			It gets the standart jQuery ajax setting option : xml, html, script, json, jsonp, test. 
		*			Or a javascriptMVC model reference. This model will be used to map  the server result to 
		*			a ready to use object by the client application. Here we are expecting an array of Resources.
		*		</p>
		*	
		*		<p>
		*			<b>success</b>
		*			The <i>success</i> callback function gets the following parameters
		*			<ul>
		*				<li>
		*					request (<a href="#!mad.net.Request">mad.net.Request</a>) : The original request setting
		*				</li>
		*				<li>
		*					response (<a href="#!mad.net.Response">mad.net.Response</a>) : The server answer
		*				</li>
		*				<li>
		*					body (<a href="#!mad.net.Response.body">mad.net.Response.body</a>) : The server body answer
		*				</li>
		*			</ul>
		*		</p>
		*		
		*		<p>
		*			<b>error</b>
		*			The <i>error</i> callback functions gets the following parameters
		*			<ul>
		*				<li>
		*					request (<a href="#!mad.net.Request">mad.net.Request</a>) : The original request setting
		*				</li>
		*				<li>
		*					response (<a href="#!mad.net.Response">mad.net.Response</a>) : The server answer
		*				</li>
		*			</ul>
		*		</p>
		*		
		* </p>
    */
	mad.core.Singleton('mad.net.Ajax',
	/** @static */
	{
		/**
		 * Perform an ajax request, parameters are similar to the ajax function of the jQuery
		 * library. Add to this one a second parameter to define if the request has to be in 
		 * a transaction or not !
		 * @param {Array} request The ajax request object
		 * @param {Boolean} isTransaction If set to true the system will try to bundle several
		 * request in one.
		 * @hide
		 */
		'request': function (setting, isTransaction) {
			return mad.net.Ajax.singleton().request(setting, isTransaction);
		}
	},

	/** @prototype */
	{

		/**
		 * Pending transactions
		 */
		'transactions': [],

		/**
		 * Current transaction Id.
		 */
		'transactionId': null,

		/**
		 * Perform an ajax request, parameters are similar to the ajax function of the jQuery
		 * library. Add to this one a second parameter to define if the request has to be in 
		 * a transaction or not !
		 * @param {Array} request The ajax request object
		 * @param {Boolean} isTransaction If set to true the system will try to bundle several
		 * request in one.
		 * @see mad.net.Ajax.static.request
		 * @hide
		 */
		'request': function (request, isTransaction) {
			if(isTransaction) {
				this._addRequest(request);
				this._executeTransaction();
			} else {
				request = mad.net.Request.setupRequest(request);
				this._executeRequest(request);
			}
		},

		/**
		 * Add a request to the current transaction
		 * @hide
		 */
		'_addRequest': function (request) {
			var transactionId = this._getTransactionId();
			if(typeof this.transactions[transactionId] == 'undefined') {
				this.transactions[transactionId] = {
					'requests': [],
					'waitFor': 0,
					'transactionId': transactionId
				};
			}

			// Identify the request
			var requestId = uuid();
			request.id = requestId;
			this.transactions[transactionId].requests[requestId] = request;
			this.transactions[transactionId].waitFor++;
			steal.dev.log('pending requests : ' + this.transactions[transactionId].waitFor);
		},

		/**
		 * Generate a new current transaction id
		 * @hide
		 */
		'_generateTransactionId': function () {
			this.transactionId = uuid();
		},

		/**
		 * Get the current transaction id, generate a new one if null
		 * @hide
		 */
		'_getTransactionId': function () {
			if(this.transactionId == null) {
				this._generateTransactionId();
			}
			return this.transactionId;
		},

		/**
		 * Get the current transaction
		 * @hide
		 */
		'_getTransaction': function (id) {
			var transactionId = typeof id != 'undefined' ? id : this._getTransactionId();
			return this.transactions[transactionId];
		},

		/**
		 * Execute the requests transaction.
		 * Here we use a timeout cycle to wait (1ms) on possible additional requests.
		 * We have to test this method without console.log, which can make the code
		 * execution sync ... etc ...
		 * @hide
		 */
		'_executeTransaction': function () {
			var self = this;
			setTimeout(function () {
				// get the current transaction
				var transaction = self._getTransaction();
				// decrease the counter of pending request
				transaction.waitFor--;
				// @debug
				steal.dev.log('pending requests : ' + transaction.waitFor);

				// if no more pending request, launche the transaction
				if(transaction.waitFor == 0) {
					// reset transaction id to allow new transaction
					self._generateTransactionId();
					// build the bundled request
					var bundleRequests = self._bundleRequests(transaction.requests);

					// execute the bundled request
					self._executeRequest({
						type: 'POST',
						url: APP_URL + '/ajax/requests',
						data: {
							'requests': bundleRequests,
							'transactionId': transaction.transactionId
						},
						dataType: 'json',
						success: function (srvData) {
							//                                srvData = $.extend(true,{},srvData);
							var transactionId = srvData.transactionId;
							var transaction = self._getTransaction(transactionId);
							for(var requestId in transaction.requests) {
								var resultRequest = new mad.net.Response(srvData.requests[requestId]);
								// execute success function of the stored request
								if(typeof transaction.requests[requestId].success != 'undefined') {
									transaction.requests[requestId].success(srvData.requests[requestId].data);
								}
							}

							// remove the transaction
							delete transaction;
							delete self.transactions[transactionId];
						}
					});

				}
			}, 1); // wait 1 ms seems to be enough
		},

		/**
		 * Execute a request. Bundled or not
		 * @hide
		 */
		'_executeRequest': function (request) {
			//				setTimeout(function(){
			$.ajax(request);
			//				}, 2000);
		},

		/**
		 * Bundle requests
		 * @hide
		 */
		'_bundleRequests': function (requests) {
			var bundle = [];
			for(var i in requests) {
				bundle.push({
					'id': requests[i].id,
					'url': requests[i].url,
					'data': requests[i].data
				});
			}
			return bundle;
		}

	});

});