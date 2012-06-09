// First Move JMVC Doc Here:

// build jmvcdoc
//load('documentjs/jmvcdoc/scripts/build.js');
load('steal/rhino/rhino.js');

steal.File("documentjs/dist/documentjs").mkdirs();
steal.File("documentjs/dist/steal/rhino").mkdirs();
steal.File("documentjs/dist/demo").mkdirs();
steal.File("documentjs/dist/steal/build").mkdirs();
steal.File("documentjs/dist/steal/dev").mkdirs();
steal.File("documentjs/dist/steal/generate").mkdirs();

steal.File("documentjs").copyTo("documentjs/dist/documentjs",".git dist demo");
steal.File("documentjs/demo").copyTo("documentjs/dist/demo",".git dist demo");
steal.File("steal/build").copyTo("documentjs/dist/steal/build",".git dist");
steal.File("steal/rhino").copyTo("documentjs/dist/steal/rhino",".git dist");
steal.File("steal/dev").copyTo("documentjs/dist/steal/dev",".git dist");
steal.File("steal/generate/ejs.js").copyTo("documentjs/dist/steal/generate/ejs.js");

steal.File("steal/steal.production.js").copyTo("documentjs/dist/steal/steal.production.js");
steal.File("steal/steal.js").copyTo("documentjs/dist/steal/steal.js");

quit();
