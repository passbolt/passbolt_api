@page StealJS.why Why StealJS
@parent StealJS.guides 0

StealJS is a dependency loader and build tool that solves the most difficult parts of building large JavaScript applications. With it's focus on simplified workflows and highly efficient production builds, we hope that StealJS is the last module loader you'll ever need. If you haven't checked out StealJS yet, we provide:

*   A [production bundler and optimizer][2] that speeds up application load times by balancing caching and resource requests.
*   [Easy workflows][3] for use with NPM and/or Bower. Less and CSS are supported out of the box.
*   A powerful [exporting tool][4] for library authors so your module can reach users regardless of whether they are using StealJS, Browserify, RequireJS, SystemJS or WebPack.
*   A module loader based on ES6, but providing compatibility with CommonJS and AMD.

## Why StealJS?

The landscape of module loaders in 2015 is vast; there are more choices today than there have ever been. At Bitovi we work with a large variety of different types of teams with their own unique workflows. We needed a modular loader that was flexible to accommodate all of these different teams. So we focused on a few key areas:

## Build Optimization

<iframe width="560" height="315" src="https://www.youtube.com/embed/C-kM0v9L9UY" frameborder="0" allowfullscreen></iframe> 

With some other module loaders you have to start thinking about production right from the beginning. If you don't you might set yourself up to have inefficient production builds. For example if your app is large enough you might worry about a large number of dependencies causing your site's initial load time to be slow. Research has shown that users respond to page load times. The faster your page loads the higher your retention is going to be. Having unused dependencies included in your production build will have an effect on your bottom line.

Likewise if you have a traditional, non single page, application you have to worry about redundancies between your different pages. StealJS provides build optimizations for both types of web sites.

### Progressive Loading

For a large single page application Steal-Tool's multi-build can break apart your application code into optimized bundles. Only the dependencies needed to render the page being viewed are loaded. As your user navigates to other areas of the site (from say a login screen to the home page) more dependencies are progressively loaded for each section they visit. This means you can more effectively cache your application as well.

To make the speedup happen, StealJS uses a unique, two pass algorithm that works great for progressively loaded pages and pages with static dependencies. The first pass aggressively splits your dependencies into bundles based on how often modules are used with other modules. This could lead to too many separate bundles and a high number of requests needed when loading a page, so the second pass of the algorithm groups bundles together for greater efficiency.

### Multi-App Build

For more traditional sites (not single page applications) you might have your apps separated into different pages. Nevertheless you don't want to build a common dependency, like Lodash, into each of these apps' production code. The multi-app build as part of Steal-Tools accounts for this as well. Like with progressive loading common resources are bundled together. So when the user navigates to /cms the CMS app will load a bundle containing jQuery and CanJS that will be reused when they later navigate to the /accounting application.

## Workflows

<iframe width="560" height="315" src="https://www.youtube.com/embed/eIfUsPdKF4A" frameborder="0" allowfullscreen></iframe> 

### Package Managers

The rise of package managers like NPM and Bower has changed the way developers write applications today. Unfortunately this alone didn't make it easier to use your dependencies in your projects. In traditional module loaders like RequireJS you would have to configure these dependencies one-by-one.

    require.config({
      paths: {
        jquery: "node_modules/jquery/dist/jquery",
        can: "node_modules/can/dist/amd/can"
     }
    });
    

This is tedious and error-prone. Once apps scale your config becomes more complex and harder to read. If any of the packages have their own dependencies you'll have to configure those as well. The result is that most client-side libraries have between 0 and 1 dependency.

Some newer loaders like Webpack and Browserify side-step this problem by requiring you use NPM with a build script. This solves the dependency problem but creates new ones. In large applications where you might have many demo and test pages you now have to create separate bundles for each of these.

We think it can be better than this. The user shouldn't have to configure their loader but they also shouldn't be forced to start off a project by writing a build script. So we built Bower and NPM plugins that allow you to use these package managers but still have the convenience that browser-based module loaders provide.

Consider you are starting a new three.js project. With npm you would install Three like so:

    npm install three --save
    

What this does in the background is save an entry in "dependencies" inside of your package.json file. When you add StealJS to your page through the script tag:

    <script src="node_modules/steal/steal.js"></script>
    

StealJS will know that you're using NPM and look up your package.json for metadata. For you it's as simple as saving and then using:

    import THREE from "three";
    

Additionally StealJS supports loading code in all popular module formats, and they can be mixed and matched. You don't ever have to care about whether your dependencies are written in CommonJS, AMD, ES6, or even browser globals, and you can choose to use whichever format works for you.

### Exporting

If you're a library author you've probably experienced the pain of building your library so that it can be consumed by all of the popular module loaders and in Node. Most projects have given up to the point where they only allow their library to be consumed in the format they themselves use and then in one other format; most likely global. Inevitably someone will request a build in a format you don't support.

It shouldn't be this difficult. As library authors you really don't care about what module loader your users choose; you want them to be able to use your code everywhere. Steal-Tools is a set of build tools that includes a way to export your project to a variety of formats and gives you complete control over what gets built and how. You can, for example, have a large project and export "foo" and "bar" as their own individual modules if needed. For most common tasks there are helpers that make it easy to export into the common format. For example you could do:

    stealTools.export({
      system: {
        config: "package.json!npm",
        main: "my/project"
      },
      outputs: {
        "+cjs": {},
        "+amd": {},
        "+global-js": {}
      }
    });
    

Which would export your code to a dist/ folder, dist/cjs, dist/amd, and dist/global for the various formats. In this example we are accepting the defaults, which is what you probably want most of the time. A full export guide is available [on stealjs.com][5]. The built CommonJS code can be used with Browserify, Webpack or StealJS. The AMD code can be used by RequireJS (or any other AMD loader including StealJS) and the global folder contains an output that doesn't require a loader at all.

## Choosing StealJS

The documentation on [stealjs.com][6] contains everything you need to explore the various features that Steal and Steal-Tools give you. The quick start guide is the best way to get started on a new project using StealJS.

Some of the things on our roadmap including full Source Map support (both for production builds and exported projects), a watch mode that will make continuous-building easier and faster, streams for use with Gulp, and hot reloading of modules.

Up until this point we have been concentrating on nailing the basics but now have room to start implementing features that will make working on your projects incrementally "nicer".

Lastly, StealJS has a long-standing commitment to backwards compatibility. Even though StealJS is now built on a completely different codebase than it was just a year ago it is mostly compatible with applications that use Legacy Steal with just a few minor config changes. We won't abandon our users when a breaking change comes; there will always be a bridge that makes upgrading your application something that can be done in a day or so.

 [1]: https://plus.google.com/events/cfrtqkdrgabil1tojif1dnlq770
 [2]: #build-optimization
 [3]: #workflows
 [4]: #exporting
 [5]: http://stealjs.com/docs/StealJS.project-exporting.html
 [6]: http://stealjs.com
