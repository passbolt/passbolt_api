/*!
 * CanJS - 2.2.9
 * http://canjs.com/
 * Copyright (c) 2015 Bitovi
 * Fri, 11 Sep 2015 23:12:43 GMT
 * Licensed MIT
 */

/*can@2.2.9#list/sort/sort*/
require('../../util/util.js');
require('../list.js');
var oldBubbleRule = can.List._bubbleRule;
can.List._bubbleRule = function (eventName, list) {
    var oldBubble = oldBubbleRule.apply(this, arguments);
    if (list.comparator && can.inArray('change', oldBubble) === -1) {
        oldBubble.push('change');
    }
    return oldBubble;
};
var proto = can.List.prototype, _changes = proto._changes, setup = proto.setup, unbind = proto.unbind;
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
    _comparatorUpdated: function (ev, newValue) {
        if (newValue || newValue === 0) {
            this.sort();
            if (this._bindings > 0 && !this._comparatorBound) {
                this.bind('change', this._comparatorBound = function () {
                });
            }
        } else if (this._comparatorBound) {
            unbind.call(this, 'change', this._comparatorBound);
            this._comparatorBound = false;
        }
    },
    unbind: function (ev, handler) {
        var res = unbind.apply(this, arguments);
        if (this._comparatorBound && this._bindings === 1) {
            unbind.call(this, 'change', this._comparatorBound);
            this._comparatorBound = false;
        }
        return res;
    },
    _comparator: function (a, b) {
        var comparator = this.comparator;
        if (comparator && typeof comparator === 'function') {
            return comparator(a, b);
        }
        return a === b ? 0 : a < b ? -1 : 1;
    },
    _changes: function (ev, attr, how, newVal, oldVal) {
        var dotIndex = ('' + attr).indexOf('.');
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
            if (typeof item !== 'undefined' && (typeof this.comparator !== 'string' || this.comparator.indexOf(changedAttr) === 0)) {
                var newIndex = this._getRelativeInsertIndex(item, currentIndex);
                if (newIndex !== currentIndex) {
                    this._swapItems(currentIndex, newIndex);
                    can.batch.trigger(this, 'length', [this.length]);
                }
            }
        }
        _changes.apply(this, arguments);
    },
    _getInsertIndex: function (item) {
        var length = this.length;
        var offset = 0;
        var a = this._getComparatorValue(item);
        var b, comparedItem;
        for (var i = 0; i < length; i++) {
            comparedItem = this[i];
            b = this._getComparatorValue(comparedItem);
            if (item === comparedItem) {
                offset = -1;
                continue;
            }
            if (this._comparator(a, b) < 0) {
                return i + offset;
            }
        }
        return length + offset;
    },
    _getRelativeInsertIndex: function (item, currentIndex) {
        var naiveInsertIndex = this._getInsertIndex(item);
        var nextItemIndex = currentIndex + 1;
        var a = this._getComparatorValue(item);
        var b;
        if (currentIndex < naiveInsertIndex && nextItemIndex < this.length) {
            b = this._getComparatorValue(this[nextItemIndex]);
            if (this._comparator(a, b) === 0) {
                return currentIndex;
            }
        }
        return naiveInsertIndex;
    },
    _getComparatorValue: function (item, overwrittenComparator) {
        var comparator = typeof overwrittenComparator === 'string' ? overwrittenComparator : this.comparator;
        if (item && comparator && typeof comparator === 'string') {
            item = typeof item[comparator] === 'function' ? item[comparator]() : item.attr(comparator);
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
    sort: function (comparator, silent) {
        var a, b, c, isSorted;
        var comparatorFn = can.isFunction(comparator) ? comparator : this._comparator;
        for (var i, iMin, j = 0, n = this.length; j < n - 1; j++) {
            iMin = j;
            isSorted = true;
            c = undefined;
            for (i = j + 1; i < n; i++) {
                a = this._getComparatorValue(this.attr(i), comparator);
                b = this._getComparatorValue(this.attr(iMin), comparator);
                if (comparatorFn.call(this, a, b) < 0) {
                    isSorted = false;
                    iMin = i;
                }
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
        if (!silent) {
            can.batch.trigger(this, 'length', [this.length]);
        }
        return this;
    },
    _swapItems: function (oldIndex, newIndex, silent) {
        var temporaryItemReference = this[oldIndex];
        [].splice.call(this, oldIndex, 1);
        [].splice.call(this, newIndex, 0, temporaryItemReference);
        if (!silent) {
            can.batch.trigger(this, 'move', [
                temporaryItemReference,
                newIndex,
                oldIndex
            ]);
        }
    }
});
var getArgs = function (args) {
    return args[0] && can.isArray(args[0]) ? args[0] : can.makeArray(args);
};
can.each({
    push: 'length',
    unshift: 0
}, function (where, name) {
    var proto = can.List.prototype, old = proto[name];
    proto[name] = function () {
        if (this.comparator && arguments.length) {
            var args = getArgs(arguments);
            var i = args.length;
            while (i--) {
                var val = can.bubble.set(this, i, this.__type(args[i], i));
                var newIndex = this._getInsertIndex(val);
                Array.prototype.splice.apply(this, [
                    newIndex,
                    0,
                    val
                ]);
                this._triggerChange('' + newIndex, 'add', [val], undefined);
            }
            can.batch.trigger(this, 'reset', [args]);
            return this;
        } else {
            return old.apply(this, arguments);
        }
    };
});
(function () {
    var proto = can.List.prototype;
    var oldSplice = proto.splice;
    proto.splice = function (index, howMany) {
        var args = can.makeArray(arguments), newElements = [], i, len;
        if (!this.comparator) {
            return oldSplice.apply(this, args);
        }
        for (i = 2, len = args.length; i < len; i++) {
            args[i] = this.__type(args[i], i);
            newElements.push(args[i]);
        }
        oldSplice.call(this, index, howMany);
        proto.push.apply(this, newElements);
    };
}());
module.exports = can.Map;
