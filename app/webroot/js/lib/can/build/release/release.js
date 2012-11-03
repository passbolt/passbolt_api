var path            = require("path"),
    fs              = require("fs"),
    spawn           = require("child_process").spawn,
    zlib            = require("zlib"),

    // Third party modules
    program         = require("commander"),
    GitHubApi       = require("github"),
    _               = require("underscore"),
    s3p             = require("./s3-post.js"),

    // Get the current version of CanJS
    version         = fs.readFileSync( path.join( __dirname, "../version" )).toString("utf8").trim(),

    // Describe all the files we'll be uploading to Github
    descriptions    = require("./descriptions.json"),

    // Figure out some paths
    rhinoPath       = path.join(__dirname, "../../.."),
    distPath        = path.join(__dirname, "../../dist/edge"),

    // Github client
    github          = new GitHubApi({
        version: "3.0.0"
    }),
    canJSRemote = "git@github.com:jupiterjs/canjs.git",

    // Timeouts
    stealTimeout,

    // For Github credentials
    username,
    password,

    // For steal build process
    pluginify;

// Get deferreds
_.mixin( require("underscore.deferred") );

var buildDfd = new _.Deferred()

// Update canjs.us dist
function updateDist() {
	process.stdout.write("Copying built files to gh-pages...");

	var clone = spawn("git", [ "clone", canJSRemote ], {
		cwd : __dirname
	}), dfd = new _.Deferred();

	clone.on("exit", function() {

		var clonePath = path.join( __dirname, "canjs" ),
		checkout = spawn("git", [ "checkout", "gh-pages"], {
				cwd : clonePath
		});

		checkout.on("exit", function() {
			var cloneReleasePath = path.join( clonePath, "release" ),
				latestPath = path.join( cloneReleasePath, "latest" ),
				versionPath = path.join( cloneReleasePath, version );

			// Make sure directories exist
			[ cloneReleasePath, versionPath, latestPath ].forEach( function( dir ) {
				if ( ! path.existsSync( dir )) {
					fs.mkdirSync( dir );
				}
			});

			fs.readdir( distPath, function( err, files ) {
				var dfds = files.map(function( file ) {
					var dfd = new _.Deferred(),
						inPath = path.join( distPath, file ),
						outPath = path.join( versionPath, file ),
						latestOutPath = path.join( latestPath, file ),
						inStream = fs.createReadStream( inPath );

					inStream.pipe( fs.createWriteStream( outPath ));
					inStream.pipe( fs.createWriteStream( latestOutPath ));

					inStream.on("end", function() {
						dfd.resolve();
					});

					return dfd.promise();

				});

				_.when.apply( _, dfds ).done(function() {

					process.stdout.write(" Done!\n");
					process.stdout.write("Pushing to Github...");
					var add = spawn("git", ["add", "release/*"], {
						cwd : clonePath
					});

					add.on("exit", function() {
						var commit = spawn("git", ["commit", "-m", "Generated release files for " + version ], {
							cwd : clonePath
						});

						commit.on("exit", function() {
							var push = spawn("git", ["push", "origin", "gh-pages"], {
								cwd : clonePath
							});

							push.on("exit", function() {
								process.stdout.write(" Done!\n");

								process.stdout.write("Cleaning up...");
								var remove = spawn("rm", ["-rf", "canjs"], {
									cwd : __dirname
								});
								process.stdout.write(" Done!\nUpdating canjs.us/release directory complete.\n");
								dfd.resolve();
							
							});
						});
					});
				});
			});
		});

	});

	return dfd.promise();
}

function createZipArchive() {

	var dfd = new _.Deferred(),
	    zip = spawn("zip", [ "-r", "edge/can.js.zip", "edge" ], {
	    	cwd : path.join( distPath, ".." )
	    });

	zip.on("exit", function() {
		dfd.resolve();
	});

	return dfd.promise();
}

// Upload files to the Github downloads page
function uploadFiles() {

	var outerDfd = new _.Deferred();

	_.when( getCredentials(), createZipArchive() ).done(function() {
		var dfds = _.map( descriptions, function( parts, filename ) {

			var dfd = new _.Deferred(),
				desc = parts.description.replace("#{VERSION}", version),
				name = parts.filename.replace("#{VERSION}", version);

			fs.readFile( path.join( distPath, filename ), function( err, buf ) {

				if ( err ) {
					console.log( err );
					process.exit(1);
				}

				github.httpSend({
					"user" : "jupiterjs",
					"repo" : "canjs",
					"name" : name,
					"size" : buf.length,
					"description" : desc,
					"content_type" : parts.content_type || "text/javascript"
				}, {
					"url": "/repos/:user/:repo/downloads",
					"method": "POST",
					"params": {
						"$user": null,
						"$repo": null,
						"$name": null,
						"$size": null,
						"description": null,
						"$content_type": null
					}
				}, function( err, socket ) {

					var data = JSON.parse( socket.data );


					s3p.postToS3({
						key : data.path,
						acl : data.acl,
						success_action_status : "201",
						Filename : data.name,
						AWSAccessKeyId : data.accesskeyid,
						policy64 : data.policy,
						signature64 : data.signature,
						contentType : data.mime_type,
						data : buf,
						bucket: "github"
					}, function( e ) {
						if ( e ) {
							console.log( e );
						}
						dfd.resolve();
					})

				});

			});
			
			dfd.resolve();
			return dfd.promise();
		});

		_.when.apply( _, dfds ).done(outerDfd.resolve.bind( outerDfd ));


	});

	return outerDfd.promise();

}

// Clean up on process exit
process.on("exit", function() {
	process.stdout.write("\n")
})

// Get Github credentials
function getCredentials() {

	var dfd = new _.Deferred();

	program.prompt("Github Username: ", function( name ) {
		username = name;

		program.password("Github Password: ", "*", function( pass ) {
			var timeout;
			password = pass;
			process.stdin.pause();

			github.authenticate({
				type: "basic",
				username: username,
				password: password
			});

			dfd.resolve();
		})

	});

	return dfd.promise();
}

function stealBuild() {

	var timeout;


	if ( ! buildDfd.isResolved() ) {

		process.stdout.write("Building CanJS " + version );

		// Run Steal build script
		pluginify = spawn( "js", ["can/util/make.js"], {
			cwd: rhinoPath
		});

		pluginify.on("exit", function() {
			clearTimeout( timeout );
			process.stdout.write(" Done!\n")
			buildDfd.resolve();
		});

		timeout = setInterval(function() {
			process.stdout.write(".");
		}, 2000);

	}

	return buildDfd.promise();
}

function choose() {
	console.log("\nWhat would you like to do?")
	program.choose([
		"Update Github Downloads", 
		"Update canjs.us/release directory", 
		"Exit"
	], function( i ) {
		if ( i == 0 ) {
			stealBuild().done(function() {
				uploadFiles().done( choose );
			});
		} else if ( i == 1 ) {
			stealBuild().done(function() {
				updateDist().done( choose )
			});
		} else if ( i == 2 ){
			console.log("Okay, exiting...")
			process.exit(0);
		}
	});
}

console.log("CanJS Release Script -- Releasing version " + version );
choose();
