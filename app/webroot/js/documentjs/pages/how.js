/**
###How DocumentJS works

DocumentJS architecture is organized 
around [DocumentJS.types | types] and [DocumentJS.tags | tags]. Types 
are meant to represent javascript constructs 
you might want to comment like 
classes (really constructors), functions and attributes. Tags 
add aditional information to the comments of the type being processed.

For example, @function is a type.  But a function's @author is a tag.

DocumentJS does the following to process your JavaScript files:

  1. Load the text of your JavaScript files. How DocumentJS finds your files can be configured 
     in several ways.

  1. Splitting the file into comment / code pairs.  
  
  1. Looking for a @type parameter in the comment or infering it from the code.
  
  1. Processing the code for other potentially documentable features such as the name of the method, arguments, etc.
  
  1. Processing the comment for tags and content.
  
  1. Saving the processed comment and tags as a JS object similar to:
  
      {
        name: "jQuery.Controller",
        type: "class",
        author: "Justin",
        ...
      }
  
  1. Adding the object by name to a global list of documented objects:
  
  
DocumentJS works by loading a set of javascript files, then by spliting each file into type/comments pairs 
and finally parsing each type's comments tag directives to produce a set of jsonp files (one per type) 
that are used by the document viewer (jmvcdoc) to render the documentation.

DocumentJS was written thinking of extensibility and it's very easy to add 
custom type/tag directives to handle your specific documentation needs.   
*/