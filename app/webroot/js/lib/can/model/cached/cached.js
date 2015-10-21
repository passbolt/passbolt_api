steal('can/model', 'can/util/object', function () {

	//!steal-remove-start
	can.dev.warn("can/model/cached is a deprecated plugin and will be removed in a future release.");
	//!steal-remove-end

	// Base model to handle reading / writing to local storage
	can.Model('can.Model.Cached', {
		setup: function () {
			can.Model.setup.apply(this, arguments);
			// setup data
			if (typeof window.localStorage !== 'undefined') {
				this._cached = JSON.parse(window.localStorage.getItem(this.cachedKey())) || {};
			} else {
				this._cached = {};
			}
		},
		cachedKey: function () {
			return 'cached' + this._shortName;
		},
		cacheClear: function () {
			window.localStorage.removeItem(this.cachedKey());
			this._cached = {};
		},
		cacheItems: function (items) {
			var data = this._cached,
				id = this.id;
			can.each(items, function (item) {
				var idVal = item[id],
					obj = data[idVal];
				if (obj) {
					can.extend(obj, item);
				} else {
					data[idVal] = item;
				}
			});
			window.localStorage.setItem(this.cachedKey(), JSON.stringify(data));
		},
		findAllCached: function (params) {
			// remove anything not filtering ....
			//   - sorting, grouping, limit, and offset
			var list = [],
				data = this._cached,
				item;
			for (var id in data) {
				item = data[id];
				if (this.filter(item, params) !== false) {
					list.push(item);
				}
			}
			// do sorting / grouping
			list = this.pagination(this.sort(list, params), params);
			// take limit and offset ...
			return list;
		},
		pagination: function (items, params) {
			var offset = parseInt(params.offset, 10) || 0,
				limit = parseInt(params.limit, 10) || items.length - offset;
			return items.slice(offset, offset + limit);
		},
		/**
		 * Sorts the object in place
		 *
		 * By default uses an order property in the param
		 * @param {Object} items
		 */
		sort: function (items, params) {
			can.each((params.order || [])
				.slice(0)
				.reverse(), function (name, i) {
					var split = name.split(' ');
					items = items.sort(function (a, b) {
						if (split[1].toUpperCase() !== 'ASC') {
							if (a[split[0]] < b[split[0]]) {
								return 1;
							} else if (a[split[0]] === b[split[0]]) {
								return 0;
							} else {
								return -1;
							}
						} else {
							if (a[split[0]] < b[split[0]]) {
								return -1;
							} else if (a[split[0]] === b[split[0]]) {
								return 0;
							} else {
								return 1;
							}
						}
					});
				});
			return items;
		},
		/**
		 * Called with the item and the current params.
		 * Should return __false__ if the item should be filtered out of the result.
		 *
		 * By default this goes through each param in params and see if it matches the
		 * same property in item (if item has the property defined).
		 * @param {Object} item
		 * @param {Object} params
		 */
		filter: function (item, params) {
			// go through each param in params
			var param, paramValue;
			for (param in params) {
				paramValue = params[param];
				// in fixtures we ignore null, I don't want to now
				if (paramValue !== undefined && item[param] !== undefined && !this._compare(param, item[param], paramValue)) {
					return false;
				}
			}
		},
		compare: {},
		_compare: function (prop, itemData, paramData) {
			return can.Object.same(itemData, paramData, this.compare[prop]);
		},
		makeFindAll: function (findAll) {
			return function (params, success, error) {
				var def = new can.Deferred(),
					// make the ajax request right away
					findAllDeferred = findAll(params),
					data = this.findAllCached(params);
				def.then(success, error);
				if (data.length) {
					var list = this.models(data);
					findAllDeferred.then(can.proxy(function (json) {
						this.cacheItems(json);
						list.attr(json, true); // TODO: update cached instances
					}, this), function () {
						can.trigger(list, 'error', arguments);
					});
					def.resolve(list);
				} else {
					findAllDeferred.then(can.proxy(function (data) {
						// Create our model instance
						var list = this.models(data);
						// Save the data to local storage
						this.cacheItems(data);
						// Resolve the deferred with our instance
						def.resolve(list);
					}, this), function (data) {
						def.reject(data);
					});
				}
				return def;
			};
		},
		makeFindOne: function (findOne) {
			return function (params, success, error) {
				var def = new can.Deferred(),
					// Make the ajax request right away
					findOneDeferred = findOne(params),
					// grab instance from cached data
					data = this._cached[params[this.id]];
				// or try to load it
				data = data || this.findAllCached(params)[0];
				// Bind success and error callbacks to the deferred
				def.then(success, error);
				// If we had existing local storage data...
				if (data) {
					// Create our model instance
					var instance = this.model(data);
					findOneDeferred.then(function (json) {
						// Update the instance when the ajax respone returns
						instance.updated(json);
					}, function (data) {
						can.trigger(instance, 'error', data);
					});
					// Resolve the deferred with our instance
					def.resolve(instance); // Otherwise hand off the deferred to the ajax request
				} else {
					findOneDeferred.then(can.proxy(function (data) {
						// Save the data to local storage
						this.cacheItems([data]);
						// Create our model instance
						var instance = this.model(data);
						// Resolve the deferred with our instance
						def.resolve(instance);
					}, this), function (data) {
						def.reject(data);
					});
				}
				return def;
			};
		}
	}, {
		updated: function (attrs) {
			// Save the model to local storage
			this.constructor.cacheItems([this.attr()]);
			// Update our model
			can.Model.prototype.updated.apply(this, arguments);
		},
		created: function (attrs) {
			// Save the model to local storage
			this.constructor.cacheItems([this.attr()]);
			// Update our model
			can.Model.prototype.created.apply(this, arguments);
		},
		destroyed: function (attrs) {
			// Save the model to local storage
			delete this.constructor._cached[this[this.constructor.id]];
			// Update our model
			can.Model.prototype.destroyed.apply(this, arguments);
		}
	});
	return can.Model.Cached;
});
