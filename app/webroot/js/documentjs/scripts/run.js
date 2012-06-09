// used to 'run' a documentjs/doc command:
load(java.lang.System.getProperty("basepath")+"../steal/rhino/utils.js")

load('steal/rhino/rhino.js');
load('documentjs/documentjs.js');

DocumentJS(_args.shift(), steal.opts(_args || {}, {out: 1}));