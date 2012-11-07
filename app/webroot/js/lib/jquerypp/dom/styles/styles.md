@page jQuery.styles
@parent jquerypp

`jQuery.styles` provides `jQuery.fn.styles` to rapidly get a set of computed styles from an element.

## Quick Example


    $("#foo").styles('float','display') //->
    // {
    //  cssFloat: "left", display: "block"
    // }

## Use

An element's __computed__ style is the current calculated style of the property.
This is different than the values on `element.style` as
`element.style` doesn't reflect styles provided by css or the browser's default
css properties.

Getting computed values individually, for example by using jQuery [.css()](http://api.jquery.com/css/), is expensive.
This plugin retrieves all needed style properties at once.

## Demo

The following demo illustrates the performance improvement `jQuery.fn.styles` provides by implementing
a faster 'height' jQuery function called 'fastHeight'.

@demo jquery/dom/styles/styles.html

