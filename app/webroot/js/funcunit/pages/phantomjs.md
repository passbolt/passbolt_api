@page funcunit.phantomjs PhantomJS
@parent funcunit.integrations 2

[http://www.phantomjs.org/ PhantomJS] is a headless WebKit. FuncUnit integrates with Phantom to run 
tests from the commandline.  This has large performance benefits, enough that it makes it much more 
feasible to integrate FuncUnit tests into your build process without significantly slowing it down.

## Install

Before you can use PhantomJS, you have to install it. The other automation tools come prepackaged in 
JMVC, but Phantom is too large of a download.

Note: There is a known issue with Phantom 1.4.0+, which will be fixed in a future release.  Until then, 
please be sure to use Phantom 1.3.0 to avoid Broken Pipe errors.  

__On Mac__

1. Download [http://code.google.com/p/phantomjs/downloads/detail?name=phantomjs-1.3.0-macosx-static-x86.zip&can=2&q= PhantomJS]
1. Unzip it somewhere like: _/Applications/_
1. Add it to your path:

@codestart
sudo ln -s /Applications/phantomjs-1.3.0/bin/phantomjs /usr/local/bin/
@codeend

_Note: Not all systems will have /usr/local/bin/.  Some systems will have: /usr/bin/, /bin/, or usr/X11/bin instead._

__On Windows__

1. Download [http://code.google.com/p/phantomjs/downloads/detail?name=phantomjs-1.3.0-win32-dynamic.zip&can=2&q= PhantomJS]
1. Install it
1. Add it to your path.  For information on setting path variable in Windows, [http://www.java.com/en/download/help/path.xml click here].

## Use

To run any test via PhantomJS, use funcunit/run

@codestart
./js funcunit/run phantomjs path/to/funcunit.html
@codeend

## Debugging

If you notice a broken test, debugging it in Phantom is not the place to start. Open the test in browser, and 
verify the same test breaks there.  If so, debug the test in browser.

If you notice the more rare event that a test breaks in Phantom but works in browser, you can use console.log 
to debug it. In <code>funcunit/commandline/phantomjs.js</code>, uncomment the print: true option. Add console.logs 
to your code and debug.

One thing to be aware of is that phantomjs tests run within an iframe in the funcunit.html page. This is different 
from the normal behavior of opening the application page using window.open. Phantom doesn't support window.open, 
so a frame is used instead. This can occassionally cause problems if your application assumes it is running within 
window.top.



