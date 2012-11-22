__1.1.1__ ( November 19, 2012 )

- Fixed [@@!!@@ Appears on Page With EJS and Table in non-IE Browsers](https://github.com/bitovi/canjs/issues/156)
- Fixed [can.deparam leaks to global scope](https://github.com/bitovi/canjs/issues/152)
- Fixed [nested attr() call on a model with List attributes blows away existing List](https://github.com/bitovi/canjs/pull/160)
- Added [https://github.com/bitovi/canjs/issues/162](https://github.com/bitovi/canjs/issues/162)
- Improved AMD support, see [#155](https://github.com/bitovi/canjs/issues/155)

__1.1.0__ ( November 13, 2012 )

 - Added [AMD module](#using_canjs-amd) support for each dependency ([#46](https://github.com/bitovi/canjs/issues/46))

 - can.util
    - Updated jQuery to 1.8.2
    - Updated Zepto to 1.0rc1
    - Updated YUI to 3.7.3

 - can.Mustache
    - Added Mustache/Handlebars support with Live Binding

 - can.view
    - Changed [passing jQuery a node list instead of a fragment in View Modifiers](https://github.com/bitovi/canjs/pull/131)

 - can.EJS
    - Fixed [the way EJS handles multiple hookups in the same attribute](https://github.com/bitovi/canjs/pull/134)
    - Fixed [Nested Loops in EJS](https://github.com/bitovi/canjs/issues/135)
    - Fixed [can.EJS template rendering issue](https://github.com/bitovi/canjs/issues/118)
    - Fixed [multiline elements in EJS](https://github.com/bitovi/canjs/pull/76)

 - can.route
    - Fixed [hashchange binding with mootools broken](https://github.com/bitovi/canjs/issues/124)

 - can.Control
    - Added [control does not listen to touchmove event on controller itself](https://github.com/bitovi/canjs/issues/104)

 - can.Observe
    - Added [List binding on .length of an object](https://github.com/bitovi/canjs/issues/142)
    - Fixed [validation error that incorrectly labels an attribute with a value of 0 as empty](https://github.com/bitovi/canjs/pull/132)
    - Added [you can now pluralise event names to listen to matching events of that type (rather than firing a single event)](https://github.com/bitovi/canjs/issues/122)
    - Added [compound sets now behave correctly](https://github.com/bitovi/canjs/issues/119)
    - Fixed [can.Observe.delegate sets wrong event.currentTarget](https://github.com/bitovi/canjs/issues/123)
    - Added [ability to assign object as attribute type in can.Observe](https://github.com/bitovi/canjs/issues/107)

 - can.Model
    - Fixed [can.Model with attributes that are models gets corrupted when you call attr()](https://github.com/bitovi/canjs/pull/141)
    - Added [missing dependency to can/model](https://github.com/bitovi/canjs/pull/140)
    - Moved can/model/elements to can/observe/elements and renamed `models` to `instances`
    - Fixed [can.Model.List doesn't fire the change event on the expando properties ](https://github.com/bitovi/canjs/issues/129)

__1.0.7__ (June 25nd 2012)

 - can.compute
      - Fixed a [global collision](https://github.com/jupiterjs/canjs/commit/7aea62462f3d8d7855f71ccdf16330e60d59f6fa) with `can.Control`.

 - Removed globals
      - Thanks [Daniel Franz](https://github.com/daniel-franz)!

__1.0.6__ (June 22nd 2012)

 - can.compute
      - Added a [computed value type object](https://github.com/jupiterjs/canjs/commit/8eb7847d410c840da38f4dd5157726e560d0a5f5) that can be used to represent several observe properties or a single static value.

 - can.ejs
      - Fixed problem with [trailing text](https://github.com/jupiterjs/canjs/commit/419248bf190febe5c3ccacb188e9c812e997278e) not being added to template.

__1.0.5__ (June 2nd 2012)

 - can.model
      - Added ability to [overwrite model crud operations](https://github.com/jupiterjs/canjs/commit/235097a46e45329d63da9b6d28a6c284c1b2a157) by defining a `make` prefixed static function, such as `makeFindAll`

 - can.EJS
      - [Fixed problem](https://github.com/jupiterjs/canjs/commit/4d4d31f12a57db1ff81f47fa0c8b4261d8133dbb) with nested block statements.

 - can.each
      - [Added optional third argument](https://github.com/jupiterjs/canjs/commit/bbd2ad5e38df90f0ebcc09a20f7ea216fe20bd72) that defines the context for the iterator function.

 - can/util/function
      - Added `can.defer` [method](https://github.com/jupiterjs/canjs/commit/64de5254ce8c284b20c3da487638497457152105) as an alias for `setTimeout(function(){}, 0)`.

 - can.view
      - Fixed `toId` [so it will work](https://github.com/jupiterjs/canjs/commit/19c9ca0f07b00afe3c99bf439c089948c46464a6) with both older and newer `steal` versions.

__1.0.4__ (May 22nd 2012)

 - Fixed plugin build process

__1.0.2__ (May 20th 2012)

 - Fixed breaking namespace issue.

__1.0.1__ (May 18th 2012)

 - can.util
     - fix: `can.each` now makes sure the [collection being iterated](https://github.com/jupiterjs/canjs/commit/c3016bc9d7075e5a31cc37576d944d9734457307) is not `undefined`

 - can.control
     - add: Redirect to another controller [method using a string](https://github.com/jupiterjs/canjs/commit/cab9b518ac0193431815ac0d34938f1168e45d5f)

 - can.model
     - fix: [Model instances in model store will be updated when `findAll` or `findOne` retrieves updated instances fixes](https://github.com/jupiterjs/canjs/commit/e4606906d37797d4ff551d1924d44f0c4d516fb7)
     - fix: Static methods such as `findOne` and `findAll` can [now be rejected](https://github.com/jupiterjs/canjs/commit/ff17833b52162348413ebdc47baaa389a90464f9). Thanks [roelmonnens](https://twitter.com/roelmonnens)!

 - can.route
    - add: Deliminating tokens now [configurable](https://github.com/jupiterjs/canjs/commit/ca98f8f2b781456a42866805e6f9879899dc38af)
    - fix: [Current route wins if all else equal](https://github.com/jupiterjs/canjs/commit/863f37cc3d34f52517050444e0b31b7d63d6c784)

__1.0__ (May 14st 2012)

 - [Registers itself as an AMD](https://github.com/jupiterjs/canjs/blob/master/util/exports.js) module if `define` is in the `window`

 - can.fixture
    - add: [a fixture plugin](https://github.com/jupiterjs/canjs/tree/5277f6f526cfa2514954d66e6f759ec73c47bf09)

 - can.util
    - add: [a util/function plugin](https://github.com/jupiterjs/canjs/commit/75e99f3b1545d4086ccdae259ccc87a3e8e7a018)

 - can.route
    - fix: [favor current route when matching](https://github.com/jupiterjs/canjs/commit/863f37cc3d34f52517050444e0b31b7d63d6c784)
    - fix: [uses defaults to match route better, and current route is not always selected](https://github.com/jupiterjs/canjs/commit/b0e59d287caba8fcb98871e4814b924588aef138)

__1.0 Beta 2__ (April 21st 2012)

 - can.util
    - change: [reverse argument order of can.each](https://github.com/jupiterjs/canjs/commit/234fd3b9eca18abdbc3fdbea114be6a818bfe6e3)
    - change/fix: [buildFragment returns non cached frag](https://github.com/jupiterjs/canjs/issues/33)
    - fix: [zepto's isEmptyObject was broke](https://github.com/jupiterjs/canjs/commit/7fe391f59a1f54e3f197f31e20276646f82e7f2e)
 - can.observe
    - feature: [recursive observes don't blow up](https://github.com/jupiterjs/canjs/issues/27)
    - change: [reverse argument order of can.each](https://github.com/jupiterjs/canjs/commit/234fd3b9eca18abdbc3fdbea114be6a818bfe6e3)
    - fix: [attr change events have old value](https://github.com/jupiterjs/canjs/commit/4081a9baf4441c1002467342baae3cdd885994c6)

 - can.model
    - fix: [findOne and findAll work with super](https://github.com/jupiterjs/canjs/commit/c93ae5478eea7fdb88fa6fc03211d81c8d4ca3bd)
    - fix: [model using custom id for store](https://github.com/jupiterjs/canjs/commit/14d05c29e71ed8c462ba49b740d9eb8e342d3c85)
    - fix: [destroy not working with templated id](https://github.com/jupiterjs/canjs/issues/32)

 - can.route
    - fix: a host of bugs in libaries other than jQuery because can.route was not properly tested in other libraries.
    - fix: can.param fixed in [dojo](https://github.com/jupiterjs/canjs/commit/77dfa012b2f6baa7dfb0fe84f2d62aeb5b04fc90),

__1.0 Beta 1__ (April 1st 2012)

Released!
