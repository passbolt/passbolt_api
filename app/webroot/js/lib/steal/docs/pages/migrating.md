@page StealJS.migrating Migrating
@parent StealJS.guides


## Migrating from old Steal

### Config changes

#### Map

There is no longer a `"*"` mapping like before:

    steal.config({
      map: {
        "*": {
          "can/util": "can/util/jquery"
        }
      }
    });

Instead flatten these out:

    steal.config({
      map: {
        "can/util": "can/util/jquery"
      }
    });

#### Paths

When specifying that a folder's children should also be pathed, include an asterisks to denote:

    steal.config({
      paths: {
        "can/": "lib/can/"
      }
    });

Add the asterisks and specify the file type:

    steal.config({
      paths: {
        "can/*": "lib/can/*.js"
      }
    });

#### Ext

CSS and Less plugins come by default, you no longer need to specify these in `steal.config`'s ext option. But do add mustache and stache if using those with CanJS.

#### Then

The old Steal was chainable using `.then`, but this produced numerous problems that are better fixed inside the config. If you need a module to load before loading another, specify this with `deps` inside of the `meta` configuration for that module.

### Build

The old Steal always produced a `production.js` file, but this is no longer the case. Though configurable, by default the new Steal will place the production file in `dist/bundles` and it will be named after your main module.

You will also need to add the following to your `stealconfig.js` file to be able to build the CanJS projects:

    System.buildConfig = {
      map: {
        "can/util/util" : "can/util/domless/domless"
      }
    };

## Paths in the less files

Steal compiles the `less` files in a slightly different way than the legacy version which affects `@import`-ing and image urls

In the legacy Steal, you `@import`-ed stuff relative from the location of the `.less` file:

    @import "../../../styles/variables.less";

In the new Steal `@import` path are relative to the steal root folder - folder that contains the `stealconfig.js` file:

    @import "styles/variables.less";

The opposite is the case for the image urls in your `.less` files:

Legacy steal (relative to the steal root folder):

    background-image: url(url/to/image.png);

New steal (relative to the .less file):

    background-image: url(../url/to/image.png);
