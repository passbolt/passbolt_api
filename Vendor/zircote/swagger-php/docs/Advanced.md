# Advanced

## Flow
* Finder crawls the filesystem
* The (Static)Analyser read the files and builds an Analysis object.
* The Analysis object is then processed by the Processors.
* The Analysis/Annotations are validated to notify the user of any known issues.
* Swagger annotation generated the swagger.json 

## Annotation Context
The annotations contain a 

## Analysis
Contains all detected annotations and other relevant meta data.

It uses a SplObjectStorage to store the annotations, which is like an array but doesn't contain duplicate entries.


