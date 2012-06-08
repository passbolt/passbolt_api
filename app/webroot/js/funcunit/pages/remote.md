@page funcunit.remote Targeting Remote Sites
@parent FuncUnit 7

FuncUnit provides the ability to run tests against any site on any domain.

## Proxy setup

Browsers have to work within the limits of the single origin policy.  Because of this, its normally not 
possible to run tests from one machine and target a site on another domain.  The way to get around this 
is with smart use of proxy servers.  FuncUnit comes with its own proxy designed for this.

For the following to work, you must first have NodeJS installed on your system.

### Windows

1. In Windows 7, click the Start button in the lower left of the screen.
1. Type "proxy".  Click the link for "Configure Proxy Server" that pops up.  
1. Click LAN settings on the bottom of the dialog.
1. Click the box for "Use a proxy server for your LAN".
1. Add address localhost and port 9999.  Click OK.
1. Navigate to javascriptmvc's folder in DOS.  Start the proxy server by typing:

    node funcunit\proxy.js
    
That's it.

## Testing a remote site

The proxy server is designed to serve any URLs requested from /funcunit, /steal, or /test from the 
filesystem, and anything else is passed through to the real server.  So the only requirement for testing a 
remote site is putting your test HTML and JS inside these directories.

An example is checked into funcunit/test/google.  Open http://www.google.com/funcunit/test/google/funcunit.html 
in your browser.  This page will be served locally.  The test will open google.com.  Since the test page and application 
page are on the same page, the funcunit commands all work as expected.

To test your own site, add a folder to /test.  Create a test and a test page.  To run it, open 
http://www.yoursite.com/test/yoursite/funcunit.html.