Imagine Component
=================

The Imagine component does the following:

 * If configured for actions it validates automatically if a valid hash from ImagineHelper was passed as named param within the url
 * Gets the hash from the url: getHash()
 * Validates the hash based on the named params: ```checkHash()```
 * Automatically unpacks the packed named args: ```unpackParams()```
