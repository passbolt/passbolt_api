@function can.view.Scope.compute compute
@parent can.view.Scope.prototype

@signature `scope.compute( key, [options] )`
@release 2.1

@param {can.mustache.key} key A dot seperated path.  Use `"\."` if you have a
property name that includes a dot.

@param {can.view.Scope.readOptions} [options] Options that configure how the `key` gets read.

@return {can.compute} A compute that can get or set `key`.