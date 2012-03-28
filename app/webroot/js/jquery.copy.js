/*----------------------------------------------------------------------------
 * jQuery Clipboard Copy
 * ---------------------------------------------------------------------------
 * jQuery:  1.2.x compatible
 * Keyword: copy,clipboard,copy all,jquery copy, jquery selector copy
 *
 * Author:  Stephen Blum
 *
 * Files:   jquery.copy.js          ## Source
 *          jquery.copy.examples.js ## Example Usage
 *          jquery.copy.min.js      ## Minified JS
 *          jquery.copy.swf         ## Flash SWF
 *          minify.pl               ## Perl JS Minifier 
 *                                  ## ./minify.pl jquery.copy.js > min.js
 *          jquery-1.2.5.min.js     ## Standard jQuery Library
 *
 * Summary: Cross-browser text copy plugin usable in the jQuery dot notation
 *          fashion $("#elmID").copy(). This plugin will copy all text inside
 *          the matching jQuery selector. There exists another implementation
 *          of this idea, however it seems a bit complex.  This plugin is
 *          much easier to use and works with jQuery's famous chains and
 *          selectors.
 * 
 * ---------------------------------------------------------------------------
 * USAGE TYPE 1: Basic (Simply copy text inside a div to the Clipboard)
 * ---------------------------------------------------------------------------
 * $("div#my-div-id").copy();
 *
 * ---------------------------------------------------------------------------
 * USAGE TYPE 2: Delimiter (Copy Multiple items with a Delimiter)
 * ---------------------------------------------------------------------------
 * $("input.copy-class").copy('\n');
 *
 * ---------------------------------------------------------------------------
 * USAGE TYPE 3: Utility (Copy JS Variable)
 * ---------------------------------------------------------------------------
 * var my_string = "text";
 * $.copy(my_string);
 *
 * ---------------------------------------------------------------------------
 * EXAMPLE 1: Copy text from a DIV on button click event.
 * ---------------------------------------------------------------------------
 * // Copy all text inside "div#my-div-element-id" div.
 * $("input#my-button-id").bind( 'click', function() {
 *     $("div#my-div-element-id").copy(); 
 * });
 *
 * ---------------------------------------------------------------------------
 * EXAMPLE 2: Copy all INPUT elements inside a form on button click.
 * ---------------------------------------------------------------------------
 * // Copy all textboxes inside "#my-div-form-id".
 * $("input#my-button-id").bind( 'click', function() {
 *     $("form#my-form-id input").copy(); 
 * });
 *----------------------------------------------------------------------------
 */
jQuery.copy = function(data) { return jQuery.fn.copy.call({}, data); };
jQuery.fn.copy = function(delimiter) {
    // Get Previous Object List
    var self = this,

    // Capture or Create Div for SWF Object
    flashcopier = (function(fid) {
        return document.getElementById(fid) || (function() {
            var divnode    = document.createElement('div');
                divnode.id = fid;
            document.body.appendChild(divnode);
            return divnode;
        })();
    })('_flash_copier'),

    // Capture our jQuery Selected Data and Scrub
    data = jQuery.map(self, function(bit) {
        return typeof bit === 'object' ? bit.value ||
                      bit.innerHTML.replace(/<.+>/g, '') : '';
    }).join( delimiter || '' ).replace(/^\s+|\s+$/g, '') || delimiter,

    // Define SWF Object with our Captured Data
    divinfo = '<embed src="jquery.copy.swf" FlashVars="clipboard='
            + encodeURIComponent(data)
            + '" width="0" height="0" '
            + 'type="application/x-shockwave-flash"></embed>';

    // Create SWF Object with Defined Data Above
    flashcopier.innerHTML = divinfo;

    // Return Previous Object List
    return self;
};
