var path          = require('path'),
	fs            = require('fs'),
	child_process = require('child_process'),
	os            = require('os'),

	// Resolve directories
	rhinoDir      = path.join( path.dirname( fs.realpathSync( __filename )), '../../..' ),
	canDir        = path.join( rhinoDir, 'can' ),
	docsDir       = path.join( canDir, "docs" ),
	doccoDir      = path.join( canDir, "util/docco" ),
	doccoOutDir   = path.join( doccoDir, "docs" ),
	sourceDir     = path.join( doccoDir, 'standalone' ),
	makePath      = path.join( doccoDir, "makestandalone.js" ),
	genCommand;

function execCommandWithOutput( command, cwd, callback ) {

	var spawn, parts;

	parts = command.split(" ");
	spawn = child_process.spawn( parts.shift(), parts, {
		cwd : cwd,
		env : process.env
	});

	["stdout", "stderr"].forEach( function( stream ) {
		spawn[stream].setEncoding("utf-8");
		spawn[stream].pipe( process[stream] );
	});

	spawn.on("exit", callback );

}

function runDocco() {

	fs.mkdir(docsDir, function() {

		fs.readdir( path.join( doccoDir, "temp" ), function( err, files ) {

			files = files.map(function( file ) {
				return path.join( "temp", file );
			});

			var command = os.platform() != "win32" ?
				"docco/bin/docco " :
				"sh docco/bin/docco ";

			console.log( "Generating docco annotated source..." );

			execCommandWithOutput( command + files.join(" "), doccoDir, function( exitCode ) {
				if ( exitCode == 0 ) {
					fs.readdir( doccoOutDir, function( err, files ) {
						console.log("Moving files into place...");
						files.forEach(function( file ) {
							console.log( "\t" + file );
							fs.renameSync( path.join( doccoDir, "docs", file ), path.join( canDir, "docs", file ));
						});
						console.log("Cleaning up...");
						["temp", "standalone", "docs"].forEach(function( dir ) {
							fs.readdir( path.join( doccoDir, dir ), function( e, files ) {
								files.forEach(function( file ) {
									fs.unlinkSync( path.join( doccoDir, dir, file ));
								});
								fs.rmdir( path.join( doccoDir, dir ));
							});
						});
						console.log("Done!");
					});
				} else {
					console.log("Error generating annotated source.");
				}
			});

		});


	});

}

function format( exitCode ) {

	if ( exitCode != 0 ) {
		console.log("Error generating unminified sources.");
		return
	}

	fs.readdir( sourceDir, function( err, files ) {

		var count = 0;

		if ( ! files.length ) {
			console.log("Error - Source directory is empty");
		}

		// Only annotate full srcs
		files = files.filter(function( file ) {
			return file.indexOf(".min.") < 0;
		});

		// Create the temp directory for stripped code
		fs.mkdir( path.join( doccoDir, "temp"), function() {

			// Generate source for all standalones
			console.log( "Stripping multiline comments and steal removes..." );
			console.log( "Converting tabs to 4 spaces." );

			files.forEach(function( file ) {
				fs.readFile( path.join( sourceDir, file ), "utf-8", function( err, code ) {
					console.log( "\t" + file );

					// Remove multiline comments
					code = code.replace( /\/\*(?:.*)(?:\n\s+\*.*)*\n/gim, "");

					// Remove double semicolons from steal pluginify
					code = code.replace( /;[\s]*;/gim, ";");
					code = code.replace( /(\/\/.*)\n[\s]*;/gi, "$1");
					
					// Tabs -> four spaces
					code = code.replace( /\t/gim, "    ");

					// Only single new lines
					code = code.replace( /(\n){3,}/gim, "\n\n");

					fs.writeFile( path.join( doccoDir, "temp", file ), code, "utf-8", function() {
						if ( ++count == files.length ) {
							runDocco();
						}
					});
				});
			});
		});
	});
}

console.log("Generating unminified sources...");

if ( os.platform() != "win32" ) {
	genCommand = "./js " + makePath;
} else {
	genCommand = "js.bat " + makePath;
}

execCommandWithOutput( genCommand, rhinoDir, format );
