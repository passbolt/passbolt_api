/*!
 * CanJS - 2.2.9
 * http://canjs.com/
 * Copyright (c) 2015 Bitovi
 * Fri, 11 Sep 2015 23:12:43 GMT
 * Licensed MIT
 */

/*can@2.2.9#util/array/makeArray*/
var can = require('./each.js');
can.makeArray = function (arr) {
    var ret = [];
    can.each(arr, function (a, i) {
        ret[i] = a;
    });
    return ret;
};
module.exports = can;
