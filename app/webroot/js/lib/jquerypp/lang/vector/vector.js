steal('jquery', function($){
	var getSetZero = function(v){ return v !== undefined ? (this.array[0] = v) : this.array[0] },
		getSetOne = function(v){ return v !== undefined ? (this.array[1] = v) : this.array[1]};

/**
 * @class jQuery.Vector
 * @parent jquerypp
 *
 * `jQuery.Vector` represents a multi dimensional vector with shorthand methods for
 * working with two dimensions.
 *
 * It is mainly used in [jQuery.event.drag drag] & [jQuery.event.drop drop] events.
 *
 * @constructor creates a new vector instance from the arguments.  Example:
 *
 *      new jQuery.Vector(1,2)
 */
	$.Vector = function(arr) {
		var array = $.isArray(arr) ? arr : $.makeArray(arguments);
		this.update(array);
	};
	$.Vector.prototype =
	/* @Prototype*/
	{
		/**
		 * Applys the function to every item in the vector and returns a new vector.
		 *
		 * @param {Function} f The function to apply
		 * @return {jQuery.Vector} A new $.Vector instance
		 */
		app: function( f ) {
			var i, newArr = [];

			for ( i = 0; i < this.array.length; i++ ) {
				newArr.push(f(this.array[i], i));
			}
			return new $.Vector(newArr);
		},
		/**
		 * Adds two vectors together and returns a new instance. Example:
		 *
		 *      new $.Vector(1,2).plus(2,3) //-> (3, 5)
		 *      new $.Vector(3,5).plus(new Vector(4,5)) //-> (7, 10)
		 *
		 * @return {$.Vector}
		 */
		plus: function() {
			var i, args = arguments[0] instanceof $.Vector ? arguments[0].array : $.makeArray(arguments),
				arr = this.array.slice(0),
				vec = new $.Vector();
			for ( i = 0; i < args.length; i++ ) {
				arr[i] = (arr[i] ? arr[i] : 0) + args[i];
			}
			return vec.update(arr);
		},
		/**
		 * Subtract one vector from another and returns a new instance. Example:
		 *
		 *      new $.Vector(4, 5).minus(2, 1) //-> (2, 4)
		 *
		 * @return {jQuery.Vector}
		 */
		minus: function() {
			var i, args = arguments[0] instanceof $.Vector ? arguments[0].array : $.makeArray(arguments),
				arr = this.array.slice(0),
				vec = new $.Vector();
			for ( i = 0; i < args.length; i++ ) {
				arr[i] = (arr[i] ? arr[i] : 0) - args[i];
			}
			return vec.update(arr);
		},
		/**
		 * Returns the current vector if it is equal to the vector passed in.
		 *
		 * `null` if otherwise.
		 *
		 * @return {jQuery.Vector}
		 */
		equals: function() {
			var i, args = arguments[0] instanceof $.Vector ? arguments[0].array : $.makeArray(arguments),
				arr = this.array.slice(0),
				vec = new $.Vector();
			for ( i = 0; i < args.length; i++ ) {
				if ( arr[i] != args[i] ) {
					return null;
				}
			}
			return vec.update(arr);
		},
		/**
		 * Returns the first value of the vector.
		 * You can also access the same value through the following aliases the
		 * [jQuery.Vector.prototype.left vector.left()] and [jQuery.Vector.prototype.left vector.width()]
		 * aliases.
		 *
		 * For example:
		 *
		 *      var v = new $.Vector(2, 5);
		 *      v.x() //-> 2
		 *      v.left() //-> 2
		 *      v.width() //-> 2
		 *
		 * @return {Number} The first value of the vector
		 */
		x: getSetZero,
		/**
		 * @hide
		 * Alias for [jQuery.Vector.prototype.x].
		 *
		 * @return {Number}
		 */
		left: getSetZero,
		/**
		 * @hide
		 * Alias for [jQuery.Vector.prototype.x].
		 *
		 * @return {Number}
		 */
		width: getSetZero,
		/**
		 * Returns the second value of the vector.
		 * You can also access the same value through the [jQuery.Vector.prototype.top vector.top()]
		 * and [jQuery.Vector.prototype.height vector.height()] aliases.
		 *
		 * For example:
		 *
		 *      var v = new $.Vector(2, 5);
		 *      v.y() //-> 5
		 *      v.top() //-> 5
		 *      v.height() //-> 5
		 *
		 * @return {Number} The first value of the vector
		 */
		y: getSetOne,
		/**
		 * @hide
		 * Alias for [jQuery.Vector.prototype.y].
		 *
		 * @return {Number}
		 */
		top: getSetOne,
		/**
		 * @hide
		 * Alias for [jQuery.Vector.prototype.y].
		 *
		 * @return {Number}
		 */
		height: getSetOne,
		/**
		 * Returns a string representation of the vector in the form of (x,y,...)
		 *
		 *      var v = new $.Vector(4, 6, 1, 3);
		 *      v.toString() //-> (4, 6, 1, 3)
		 *
		 * @return {String}
		 */
		toString: function() {
			return "(" + this.array.join(', ') + ")";
		},
		/**
		 * Replaces the vectors contents
		 *
		 *      var v = new $.Vector(2, 3);
		 *
		 * @param {Object} array
		 */
		update: function( array ) {
			var i;
			if ( this.array ) {
				for ( i = 0; i < this.array.length; i++ ) {
					delete this.array[i];
				}
			}
			this.array = array;
			for ( i = 0; i < array.length; i++ ) {
				this[i] = this.array[i];
			}
			return this;
		}
	};

	$.Event.prototype.vector = function() {
		// Get the first touch element for touch events
		var touches = "ontouchend" in document && this.originalEvent.touches && this.originalEvent.touches.length
				? this.originalEvent.changedTouches[0] : this;
		if ( this.originalEvent.synthetic ) {
			var doc = document.documentElement,
				body = document.body;
			return new $.Vector(touches.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc.clientLeft || 0),
				touches.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc.clientTop || 0));
		} else {
			return new $.Vector(touches.pageX, touches.pageY);
		}
	};

	$.fn.offsetv = function() {
		if ( this[0] == window ) {
			return new $.Vector(window.pageXOffset ? window.pageXOffset : document.documentElement.scrollLeft, window.pageYOffset ? window.pageYOffset : document.documentElement.scrollTop);
		} else {
			var offset = this.offset();
			return new $.Vector(offset.left, offset.top);
		}
	};

	$.fn.dimensionsv = function( which ) {
		if ( this[0] == window || !which ) {
			return new $.Vector(this.width(), this.height());
		}
		else {
			return new $.Vector(this[which + "Width"](), this[which + "Height"]());
		}
	};

	return $;
});
