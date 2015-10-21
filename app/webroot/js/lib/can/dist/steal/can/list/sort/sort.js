/*!
 * CanJS - 2.2.9
 * http://canjs.com/
 * Copyright (c) 2015 Bitovi
 * Fri, 11 Sep 2015 23:12:43 GMT
 * Licensed MIT
 */

/*can@2.2.9#list/sort/sort*/
steal('can/util', 'can/list', function () {

	// BUBBLE RULE
	// 1. list.bind("change") -> bubbling
	//    list.unbind("change") -> no bubbling
	
	// 2. list.attr("comparator","id") -> nothing
	//    list.bind("length") -> bubbling
	//    list.removeAttr("comparator") -> nothing
	
	// 3. list.bind("change") -> bubbling
	//    list.attr("comparator","id") -> bubbling
	//    list.unbind("change") -> no bubbling
	


	// 4. list.bind("length") -> nothing 
	//    list.attr("comparator","id") -> bubbling
	//    list.removeAttr("comparator") -> nothing
	
	// 5. list.bind("length") -> nothing 
	//    list.attr("comparator","id") -> bubbling
	//    list.unbind("length") -> nothing

	// Change bubble rule to bubble on change if there is a comparator.
	var oldBubbleRule = can.List._bubbleRule;
	can.List._bubbleRule = function(eventName, list) {
		var oldBubble = oldBubbleRule.apply(this, arguments);

		if (list.comparator && can.inArray('change', oldBubble) === -1) {
			oldBubble.push('change');
		}

		return oldBubble;
	};

	var proto = can.List.prototype,
		_changes = proto._changes,
		setup = proto.setup,
		unbind = proto.unbind;

	//Add `move` as an event that lazy-bubbles

	// extend the list for sorting support

	can.extend(proto, {
		setup: function (instances, options) {
			setup.apply(this, arguments);
			this._comparatorBound = false;
			this._init = 1;
			this.bind('comparator', can.proxy(this._comparatorUpdated, this));
			delete this._init;
			
			if (this.comparator) {
				this.sort();
			}
		},
		_comparatorUpdated: function(ev, newValue){
			if( newValue || newValue === 0 ) {
				this.sort();
				
				if(this._bindings > 0 && ! this._comparatorBound) {
					this.bind("change", this._comparatorBound = function(){});
				}
			} else if(this._comparatorBound){
				unbind.call(this, "change", this._comparatorBound);
				this._comparatorBound = false;
				
			}
			
			// if anyone is listening to this object
		},
		unbind: function(ev, handler){
			var res = unbind.apply(this, arguments);
			
			if(this._comparatorBound && this._bindings === 1) {
				unbind.call(this,"change", this._comparatorBound);
				this._comparatorBound = false;
			}
			
			return res;
		},
		_comparator: function (a, b) {
			var comparator = this.comparator;

			// If the user has defined a comparator, use it
			if (comparator && typeof comparator === 'function') {
				return comparator(a, b);
			}

			return a === b ? 0 : a < b ? -1 : 1;
		},
		_changes: function (ev, attr, how, newVal, oldVal) {
			var dotIndex = ("" + attr).indexOf('.');

			// If a comparator is defined and the change was to a
			// list item, consider moving the item.
			if (this.comparator && dotIndex !== -1) {
				if (ev.batchNum) {
					if (ev.batchNum === this._lastProcessedBatchNum) {
						return;
					} else {
						this.sort();
						this._lastProcessedBatchNum = ev.batchNum;
						return;
					}
				}
	
				var currentIndex = +attr.substr(0, dotIndex);
				var item = this[currentIndex];
				var changedAttr = attr.substr(dotIndex + 1);

				// Don't waste time evaluating items in ways that aren't
				// relevant or have changed in ways that aren't relevant.
				if (typeof item !== 'undefined' &&
					(typeof this.comparator !== 'string' ||
						this.comparator.indexOf(changedAttr) === 0)) {
	
					// Determine where this item should reside as a result
					// of the change
					var newIndex =
						this._getRelativeInsertIndex(item, currentIndex);

					if (newIndex !== currentIndex) {
						this._swapItems(currentIndex, newIndex);
	
						// Trigger length change so that {{#block}} helper
						// can re-render
						can.batch.trigger(this, 'length', [
							this.length
						]);
					}
	
				}
			}
			_changes.apply(this, arguments);
		},
		/**
		 * @hide
		 */
		_getInsertIndex: function (item) {
			var length = this.length;
			var offset = 0;
			var a = this._getComparatorValue(item);
			var b, comparedItem;

			for (var i = 0; i < length; i++) {
				comparedItem = this[i];

				b = this._getComparatorValue(comparedItem);

				// The index(i) will increment even though the current
				// item isn't a candidate. If we ignored this, it would
				// suggest moving the item after itself. Which would look like
				// this:
				//   [1(a, b), 2, 3] // i = 0; a === b; Don't swap;
				//   [1(a), 2(b), 3] // i = 1; a < b; Do swap (a) from 0 to 1;
				//   [2, 3] // splice(0, 1)
				//   [2, 1, 3] // splice(1, 0, a)
				if (item === comparedItem) {
					offset = -1;
					continue;
				}

				// If we've found an item ranked greater than this
				// item, consider this a good "insert" index.
				if (this._comparator(a, b) < 0) {
					return i + offset;
				}
			}

			// Move the item to the end of the list
			return length + offset;
		},

		_getRelativeInsertIndex: function (item, currentIndex) {
			var naiveInsertIndex = this._getInsertIndex(item);
			var nextItemIndex = currentIndex + 1;
			var a = this._getComparatorValue(item);
			var b;

			// If a forward swap is suggested by _getInsertIndex, inspect
			// the next item for the same value. Otherwise, we may be
			// needlessly leapfroging over same value items to be naively
			// positioned before an item with a greater value. Otherwise,
			// the naiveInsertIndex is totally valid.
			if (currentIndex < naiveInsertIndex && nextItemIndex < this.length) {
				b = this._getComparatorValue(this[nextItemIndex]);

				if (this._comparator(a, b) === 0) {
					return currentIndex;
				}
			}

			return naiveInsertIndex;
		},

		_getComparatorValue: function (item, overwrittenComparator) {

			// Use the value passed to .sort() as the comparator value
			// if it is a string
			var comparator = typeof overwrittenComparator === 'string' ?
				overwrittenComparator :
				this.comparator;

			// If the comparator is a string use that value to get
			// property on the item. If the comparator is a method,
			// it'll be used elsewhere.
			if (item && comparator && typeof comparator === 'string') {
				item = typeof item[comparator] === 'function' ?
					item[comparator]() :
					item.attr(comparator);
			}

			return item;
		},

		_getComparatorValues: function () {
			var self = this;
			var a = [];
			this.each(function (item, index) {
				a.push(self._getComparatorValue(item));
			});
			return a;
		},

		/**
		 * @hide
		 */
		sort: function (comparator, silent) {
			var a, b, c, isSorted;

			// Use the value passed to .sort() as the comparator function
			// if it is a function
			var comparatorFn = can.isFunction(comparator) ?
				comparator :
				this._comparator;

			for (var i, iMin, j = 0, n = this.length; j < n-1; j++) {
				iMin = j;

				isSorted = true;
				c = undefined;

				for (i = j+1; i < n; i++) {

					a = this._getComparatorValue(this.attr(i), comparator);
					b = this._getComparatorValue(this.attr(iMin), comparator);

					// [1, 2, 3, 4(b), 9, 6, 3(a)]
					if (comparatorFn.call(this, a, b) < 0) {
						isSorted = false;
						iMin = i;
					}

					// [1, 2, 3, 4, 8(b), 12, 49, 9(c), 6(a), 3]
					// While iterating over the unprocessed items in search
					// of a "min", attempt to find two neighboring values
					// that are improperly sorted.
					// Note: This is not part of the original selection
					// sort agortithm
					if (c && comparatorFn.call(this, a, c) < 0) {
						isSorted = false;
					}

					c = a;
				}

				if (isSorted) {
					break;
				}

				if (iMin !== j) {
					this._swapItems(iMin, j, silent);
				}
			}


			if (! silent) {
				// Trigger length change so that {{#block}} helper can re-render
				can.batch.trigger(this, 'length', [this.length]);
			}

			return this;
		},

		/**
		 * @hide
		 */
		_swapItems: function (oldIndex, newIndex, silent) {

			var temporaryItemReference = this[oldIndex];

			// Remove the item from the list
			[].splice.call(this, oldIndex, 1);

			// Place the item at the correct index
			[].splice.call(this, newIndex, 0, temporaryItemReference);

			if (! silent) {
				// Update the DOM via can.view.live.list
				can.batch.trigger(this, 'move', [
					temporaryItemReference,
					newIndex,
					oldIndex
				]);
			}
		}
		
	});
	// create push, unshift
	// converts to an array of arguments
	var getArgs = function (args) {
		return args[0] && can.isArray(args[0]) ? args[0] : can.makeArray(args);
	};
	can.each({
			/**
			 * @function push
			 * Add items to the end of the list.
			 *
			 *     var l = new can.List([]);
			 *
			 *     l.bind('change', function(
			 *         ev,        // the change event
			 *         attr,      // the attr that was changed, for multiple items, "*" is used
			 *         how,       // "add"
			 *         newVals,   // an array of new values pushed
			 *         oldVals,   // undefined
			 *         where      // the location where these items where added
			 *         ) {
			 *
			 *     })
			 *
			 *     l.push('0','1','2');
			 *
			 * @param {...*} [...items] items to add to the end of the list.
			 * @return {Number} the number of items in the array
			 */
			push: "length",
			/**
			 * @function unshift
			 * Add items to the start of the list.  This is very similar to
			 * [can.List::push].  Example:
			 *
			 *     var l = new can.List(["a","b"]);
			 *     l.unshift(1,2,3) //-> 5
			 *     l.attr() //-> [1,2,3,"a","b"]
			 *
			 * @param {...*} [...items] items to add to the start of the list.
			 * @return {Number} the length of the array.
			 */
			unshift: 0
		},
		// adds a method where
		// @param where items in the array should be added
		// @param name method name
		function (where, name) {
			var proto = can.List.prototype,
				old = proto[name];
			proto[name] = function () {

				if (this.comparator && arguments.length) {
					// get the items being added
					var args = getArgs(arguments);
					var i = args.length;

					while (i--) {
						// Go through and convert anything to an `map` that needs
						// to be converted.
						var val = can.bubble.set(this, i, this.__type(args[i], i) );

						// Insert this item at the correct index
						var newIndex = this._getInsertIndex(val);
						Array.prototype.splice.apply(this, [newIndex, 0, val]);

						this._triggerChange('' + newIndex, 'add', [val], undefined);
					}

					can.batch.trigger(this, 'reset', [args]);

					return this;
				} else {
					// call the original method
					return old.apply(this, arguments);
				}



			};
		});

	// Overwrite .splice so that items added to the list (no matter what the
	// defined index) are inserted at the correct index, while preserving the
	// ability to remove items from a list.
	(function () {
		var proto = can.List.prototype;
		var oldSplice = proto.splice;

		proto.splice = function (index, howMany) {

			var args = can.makeArray(arguments),
				newElements =[],
				i, len;

			// Don't use this "sort" oriented splice unless this list has a
			// comparator
			if (! this.comparator) {
				return oldSplice.apply(this, args);
			}

			// Get the list of new items intended to be added to the list
			for (i = 2, len = args.length; i < len; i++) {
				args[i] = this.__type(args[i], i);
				newElements.push(args[i]);
			}

			// Remove items using the original splice method
			oldSplice.call(this, index, howMany);

			// Add items by way of push so that they're sorted into
			// the correct position
			proto.push.apply(this, newElements);
		};
	})();


	return can.Map;
});

