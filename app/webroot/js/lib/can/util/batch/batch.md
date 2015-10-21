@page can.batch
@parent can.util


[can.batch.start `can.batch.start( batchStopHandler )`] and
[can.batch.stop `can.batch.stop( force, callStart )`] are used to specify
atomic operations. `start`
prevents change events from being fired until `stop` is called.

This `player` has a favorite `tvshow`. We are listening for any changes to her
preferences.

    var player = new can.Map({
      tvshow: "The Simpsons"
    });

    player.bind("change", function(ev, attr, how, newVal, oldVal) {
      console.log("[change triggered] " + how + ": " + attr);
    });

Normally, if a favorite `tvshow` were replaced with a favorite `song`, the
"change" callback handler would immediately be called when `tvshow` is removed
and when `song` is added.

By incorporating `can.batch`, the calls to the "change" callback handler will
not occur until after `tvshow` is removed, `song` is added, and
`can.batch.stop` is called.

    can.batch.start();

    player.removeAttr("tvshow");
    player.attr("song", "What makes you beautiful");

    can.batch.stop();

Performance and correctness are the two most common reasons
to use batch operations.

## Correctness

Sometimes, an object can temporarily be in an invalid
state. For example, the previous `player` should have
a `tvshow` or `song` property, but not both. Event listeners should
never be called in an intermediate state. The
[can.Map.prototype.define `can/map/define`] plugin uses `can.batch.start` and
`can.batch.stop` to accomplish this when calling a `setter`.

    import "can/map/define/"

    var Player = can.Map.extend({
      define: {
        tvshow: {
          set: function(newValue) {
            this.removeAttr("song");

            return newValue;
          }
        },
        song: {
          set: function(newValue) {
            this.removeAttr("tvshow");

            return newValue;
          }
        }
      }
    });

    var player = new Player({ song: "Amish Paradise" });

    player.bind("change", function(ev, attr, how, newVal, oldVal){
      var song = this.attr("song");
      var tvshow = this.attr("tvshow");

      if(song) {
        console.log("The greatest song is " + song + ". TV is overrated.");
      }
      if(tvshow) {
        console.log("The greatest TV show is " + tvshow +
          ". Music is overrated.");
      }
    });

    player.attr("tvshow", "Breaking Bad");

Use `can.batch.start` and `can.batch.stop` to ensure that events
are only triggered when a subject is in a valid state.

## Performance

CanJS synchronously dispatches events when a property changes.
This makes certain patterns easier. For example, if you
are utilizing live-binding and change a property, the DOM is
immediately updated.

Occasionally, you may find yourself changing many properties at once. To
prevent live-binding from performing unnecessary updates,
update the properties within a pair of calls to `can.batch.start` and
`can.batch.stop`.

Consider this list of items.

    var items = new Items([
      {selected: false},
      {selected: true},
      {selected: false}
    ]);

This template renders the number of selected items.

    var renderer = can.stache("{{count}}");

    $("#itemCount").html(renderer({
      count: function() {
        var count = 0;
        items.each(function(item) {
          count += item.attr("selected") ? 1 : 0;
        });
        console.log(count);
        return count;
      }
    }));

Using `can.batch` will ensure that the DOM is only updated once the whole list
of items has been updated instead of every time an individual item is flipped.

    $("#selectAll").click(function() {
      can.batch.start();
      items.each(function(item){
        item.attr('selected', true);
      });
      can.batch.stop();
    });

## batchNum

All events created within a set of `start` / `stop` calls share the same
batchNum value. This can be used to respond only once for a given batchNum.

    var batchNum;
    obs.bind("change", function(ev, attr, how, newVal, oldVal) {
      if(!ev.batchNum || ev.batchNum !== batchNum) {
        batchNum = ev.batchNum;
        // your code here!
      }
    });

## Automatic Batching

Libraries like Angular and Ember always batch operations. This behavior can be
reproduced by batching everything that happens within the same thread of
execution and/or within 10ms of each other.

    can.batch.start();
    setTimeout(function() {
      can.batch.stop(true, true);
      setTimeout(arguments.callee, 10);
    }, 10);
