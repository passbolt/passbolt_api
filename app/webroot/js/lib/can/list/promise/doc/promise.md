@function can.List.plugins.promise promise
@parent can.List.plugins
@release 2.1

Adds observable promise methods to [can.List].

## Use

The `can/list/promise` plugin adds observable promise methods 
to [can.List]. These methods let you know when the list data has been
resolved, rejected, or is still pending.

The following demo illustrates how [can.List::isPending isPending],
[can.List::isResolved isResolved] and [can.List::isRejected isRejected]
are used to show the state of the application loading a list. Notice
how:

 - `Loading` is shown as the list is loading.
 - `Error` is shown when an error on the server happens.
 - `No items` is shown when there are no items returned by the server.

@demo can/list/promise/promise.html

