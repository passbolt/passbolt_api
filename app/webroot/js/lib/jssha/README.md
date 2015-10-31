# jsSHA
A JavaScript implementation of the complete Secure Hash Standard family
	(SHA-1, SHA-224, SHA-256, SHA-384, and SHA-512) as well as HMAC by
	Brian Turek

## About
jsSHA is a javaScript implementation of the complete Secure Hash Algorithm
family as defined by FIPS PUB 180-2
(http://csrc.nist.gov/publications/fips/fips180-2/fips180-2withchangenotice.pdf).

It also includes the HMAC algorithm with SHA support as defined by FIPS PUB 198-1
(http://csrc.nist.gov/publications/fips/fips198-1/FIPS-198-1_final.pdf)

With the slow phasing out of MD5 as the standard hash to use in web
applications, a client-side implementation of the complete Secure Hash Standard
family was needed.  Due to SHA-384 and SHA-512's use of 64-bit values throughout
the algorithm, JavaScript can not easily natively support the calculation of
these hashes.  As a result, a bit of hacking had to be done to make sure the
values behaved themselves. SHA-224 was added to the Secure Hash Standard family
on 25 February 2004 so it was also included in this package.

## Files
**src/sha_dev.js**

A commented implementation of the entire SHA family of hashes. Not to be used
in production.

**src/sha.js**

A Google Closure Compiler optimized version of the entire library

**src/sha1.js**

A Google Closure Compiler optimized version the library with non SHA-1
functionality removed

**src/sha256.js**

A Google Closure Compiler optimized version the library with non SHA-224/SHA-256
functionality removed

**src/sha512.js**

A Google Closure Compiler optimized version the library with non SHA-384/SHA-512
functionality removed

**test/test.html**

A test page that calculates various hashes and has their correct values

**test/genHashRounds.py**

A Python2 script that generates multi-round hash values

**build/make-release**

A Bash script that runs the various Google Closure Compiler commands to build
a release

**build/externs.js**

File needed solely to make the Google Closure Compilter work

## Usage

### Browser
Include the desired JavaScript file (sha.js, sha1.js, sha256.js, or sha512.js)
in your header (sha.js used below):

	<script type="text/javascript" src="/path/to/sha.js"></script>

#### Hashing
Instantiate a new jsSHA object with the desired hash type, input type, and
options as parameters.  The hash type can be one of SHA-1, SHA-224, SHA-256,
SHA-384, or SHA-512.  The input type can be one of HEX, TEXT, B64, or BYTES.
You can then stream in input using the "update" object function.  Finally,
simply call "getHash" with the output type as a parameter (B64, HEX, or BYTES).
Example to calculate the SHA-512 of "This is a test":

	var shaObj = new jsSHA("SHA-512", "TEXT");
	shaObj.update("This is a test");
	var hash = shaObj.getHash("HEX");

The constructor takes a hashmap as a optional third argument with possible
properties of "numRounds" and "encoding".  numRounds controls the number of
hashing iterations/rounds performed and defaults to a value of "1" if not
specified. encoding specifies the encoding used to encode TEXT-type inputs.
Valid options are "UTF8", "UTF16BE", and "UTF16LE", it defaults to "UTF8".

getHash also takes a hashmap as an optional second argument.  By default the
hashmap is `{"outputUpper" : false, "b64Pad" : "="}`.  These options are
intelligently interpreted based upon the chosen output format.

#### HMAC
Instantiate a new jsSHA object the same way as for hashing.  Then set the HMAC
key to be used by calling "setHMACKey" with the key and its input type (this
MUST be done before calling update).  You can stream in the input using the
"update" object function just like hashing.  Finally, get the HMAC by calling
the "getHMAC" function with the output type as its argument.  Example to
calculate the SHA-512 HMAC of the string "This is a test" with the key "abc":

	var shaObj = new jsSHA(hashType, "TEXT");
	shaObj.setHMACKey("abc", "TEXT");
	shaObj.update("This is a test");
	var hmac = shaObj.getHMAC("HEX");

setHMACKey takes the same input types as the constructor and getHMAC takes the
same inputs as "getHash" as described above.

Note: You cannot calculate both the hash and HMAC using the same object.

### Node.js
jsSHA is available through NPM and be installed by simply doing

	npm install jssha
To use the module, first require it using:

	jsSHA = require("jssha");

The rest of the instructions are identical to the [Browser](#browser) section above.

## Compiling
This library makes use of the Google Closure Compiler
(https://developers.google.com/closure/compiler) to both boost performance
and reduce filesizes.  To compile sha_dev.js into a customized output file, use
a command like the following:

	java -jar compiler.jar --define="SUPPORTED_ALGS=<FLAG>" \
		--externs /path/to/build/externs.js --warning_level VERBOSE \
		--compilation_level ADVANCED_OPTIMIZATIONS \
		--js /path/to/sha_dev.js --js_output_file /path/to/sha.js

where FLAG is a bitwise OR of the following values:

* 4 for SHA-384/SHA-512
* 2 for SHA-224/256
* 1 for SHA-1

## Contact Info
The project's website is located at [http://caligatio.github.com/jsSHA/](http://caligatio.github.com/jsSHA/)
