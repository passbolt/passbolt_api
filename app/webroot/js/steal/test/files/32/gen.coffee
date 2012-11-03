fs = require "fs"

for i in [0..32]
  val = "0#{i}".slice( -2 ).toString()
  text = "#thirtytwo .div#{i} { color: #0#{val}; }\n"
  fs.writeFile "#{i}.css", text
