@page jQuery.animate
@parent jquerypp

`jQuery.animate` overwrites `[jQuery.fn.animate jQuery.fn.animate(properties, duration, callback)]`
and enables it to animate properties using CSS 3 animations, if supported.
If the browser doesn't support CSS animations, the original [jQuery.fn.animate()](http://api.jquery.com/animate/) will be used.

Using browser CSS animations, which can make use of hardware acceleration,
can improve your application performance especially on mobile devices (like the Webkit based
default browsers for iPhone, iPad and Android devices).

## Compatibility

`jQuery.fn.animate` is mostly compatible with the original [jQuery.fn.animate()](http://api.jquery.com/animate/)
which will be used as a fallback when

- The browser doesn't support CSS transitions
- A property is set to `show` or `hide` which is used by jQuery internally to set the original property
- The properties are empty
- The elements are not DOM nodes (e.g. created with `$({ test : 'object' })`)
- The element is displayed `inline`

## Example

The following example creates a fade-in effect using CSS animations:

    $('#element').css({
      opacity : 0
    }).anifast({
      opacity : 1
    }, 1000, function() {
      console.log('Animation done');
    });

If you want to force a jQuery animation pass the `jquery` option. The animation callback gets passed `true` if
the animation has been done using CSS animations:

    $('#element').css({
      opacity : 0,
      jquery : true
    }).anifast({
      opacity : 1
    }, 1000, function(usedCss) {
      console.log('Animation done');
      if(!usedCss) {
        console.log('Used jQuery animation');
      }
    });

## Demo

The following demo is based on the [jQuery .animate reference](http://api.jquery.com/animate/) but uses CSS animations:

@demo jquery/dom/animate/animate.html 400
