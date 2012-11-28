/**
 * Request Manager Object
 *
 * Easily Manage HTTP traffic and All Ajax requests
 * Supports Priority and Age Promotion.
 *
 * Works with Mootools 1.2
 */

// Priority Queue Object for ordering 
// arrays of objects
//
// @param function compare Custom Compare function optional.
var PriorityQueue = function(compare) {
	this._items = [];
	if ( typeof compare == 'function') {
		this._compare = compare;
	}
}
PriorityQueue.prototype = {
	// Default compare function
	//
	// Compare val1 > val2
	_compare : function(val1, val2) {
		if (val1 < val2) {
			return -1;
		}
		if (val1 > val2) {
			return 1;
		} 
		return 0;
	},

	// Sort items in queue.
	prioritize : function() {
		this._items.sort(this._compare);
	},

	// retrieve an item from the queue (removes from queue as well)
	get : function() {
		return this._items.shift();
	},

	// Look at the next element in the queue
	peek : function() {
		return this._items[0];
	},

	// Get an Item in a position (does not remove)
	//
	// @param int Position in queue you want.
	item : function(position) {
		return this._items[position];
	},

	// Append to the queue. Prioiritize queue as well.
	//
	// @param mixed item Object to append to the queue
	put : function(item){
		this._items.push(item);
		this.prioritize();
	},

	// Delete an object from the queue
	//
	// @param mixed val Object to remove
	remove : function(val) {
		for (var i = 0; i < this._items.length; i++) {
			if (this._items === val){
				this._items.splice(i, 1);
				return true;
			}
		}
		return false;
	},

	// Returns size of items array
	size : function() {
		return this._items.length;
	}
	
};

// Request Manager Object,
var RequestManager = (function(){
	var Manager = {
		_active : [], //number of active requests Maxes at 2 simultaneous requests (HTTP limit)
		defaultPriority : 10,
		interval : 250, //milliseconds between queue activations
		ageLimit : 60000, //max time a request can live in the queue.

		// Create the internal queue
		_pending : new PriorityQueue(function (request1, request2) {
			return request1.priority - request2.priority;
		}),

		// Send a request.  Adds the request to the queue.
		//
		// @param object Request Description of request See mootools::Remote
		// 
		// Options: 
		//
		// - url - (string: defaults to null) The URL to request.
		// - method - (string: defaults to 'post') The HTTP method for the request, can be either 'post' or 'get'.
		// - data - (string: defaults to '') The default data for , used when no data is given.
		// - async - (boolean: defaults to true) If set to false, the requests will be synchronous and freeze the browser during request.
		// - encoding - (string: defaults to "utf-8") The encoding to be set in the request header.
		// - autoCancel - (boolean: defaults to false) When set to true, automatically cancels the already running request if another one is sent. Otherwise, ignores any new calls while a request is in progress.
		// - headers - (object) An object to use in order to set the request headers.
		// - isSuccess - (function) Overrides the built-in isSuccess function
		//
		send : function (request) {
			if (typeof request.priority != 'number') {
				request.priority = this.defaultPriority;
			}
			request.age = 0;
			request.active = false;
			this._pending.put(request);
		},

		// Send the next request in the queue
		_sendNext : function () {
			if ( this._active.length < 2) {
				var request = this._pending.get();
				if (request) {
					request.transport = new Request(request);
					request.transport.send(request.data);
					this._active.push(request);
				}
			}
		},

		//promote request inthe queue based on age
		_agePromote : function () {
			for (var i = 0; i < this._pending.size(); i++) {
				var request = this._pending.item(i);
				request.age += this.interval;
				if ( request.age >= this.ageLimit) {
					request.age = 0;
					request.priority--;
				}
			}
			this._pending.prioritize();
		},

		//Check the active running requests
		_checkActiveRequests : function () {
			var request = null;
			var transport = null;
			//reverse loop.
			for (var i = this._active.length - 1; i >= 0; i--) {
				request = this._active[i];
				transport = request.transport;
				//if done, remove from the queue.
				if (transport.running === false) {
					request.active = false;
					this._active.splice(i, 1);
				}
			}
		},

		// Cancel A Request whether it is running or not.
		cancel : function(request) {
			if (!this._pending.remove(request)) {
				request.transport.cancel();
				if (this._active[0] === request) {
					this._active.shift();
				}
				if (this._active[1] === request) {
					this._active.pop();
				}
			}
		},

		// PreFetch Do Ajax requests in the background
		prefetch : function (request) {
			request.priority = 5;
			this.send(request);
		},
		
		// User. Do user requests, high priority.
		user : function (request) {
			request.priority = 0;
			this.send(request);
		}
	
	}
	setInterval(function () {
		RequestManager._checkActiveRequests();
		RequestManager._agePromote();
		RequestManager._sendNext();
	}, Manager.interval);
	
	return Manager;
} )();