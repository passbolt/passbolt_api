@function can.view.Scope.computeData computeData
@parent can.view.Scope.prototype

@param {can.mustache.key} key A dot seperated path.  Use `"\."` if you have a
property name that includes a dot.

@param {can.view.Scope.readOptions} [options] Options that configure how the `key` gets read.

@return {{}} An object with the following values:

@option {can.compute} compute A compute that returns the
value of `key` looked up in the scope's context or parent context. This compute can
also be written to, which will set the observable attribute or compute value at the
location represented by the key.

@option {can.view.Scope} scope The scope the key was found within. The key might have
been found in a parent scope.

@option {*} initialData The initial value at the key's location.

@body

## Use

`scope.computeData(key, options)` is used heavily by [can.mustache] to get the value of
a [can.mustache.key key] value in a template. Configure how it reads values in the
scope and what values it returns with the [can.view.Scope.readOptions options] argument.

    var context = new Map({
      name: {first: "Curtis"}
    })
    var scope = new can.view.Scope(context)
    var computeData = scope.computeData("name.first");

    computeData.scope === scope //-> true
    computeData.initialValue    //-> "Curtis"
    computeData.compute()       //-> "Curtis"

The `compute` value is writable.  For example:

    computeData.compute("Andy")
    context.attr("name.first") //-> "Andy"