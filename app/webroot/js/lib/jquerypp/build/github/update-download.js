var path = require("path"),
	fs = require("fs"),
	spawn = require("child_process").spawn,

// Third party modules
	program = require("commander"),
	GitHubApi = require("github"),
	_ = require("underscore"),
	s3p = require("./s3-post.js"),
	mime = require('mime'),

// Get the current version
	version = fs.readFileSync(path.join(__dirname, "../version")).toString("utf8").trim(),

// Describe all the files we'll be uploading to Github
	descriptions = {
		"jquerypp.js" : "jQuery++ #{VERSION} - All plugins",
	},

// Figure out some paths
	rhinoPath = path.join(__dirname, "../../.."),
	distPathShort = "jquery/dist/",
	distPath = path.join(__dirname, "../../dist"),

// Github client
	github = new GitHubApi({
		version : "3.0.0"
	}),
	remote = "git@github.com:jupiterjs/jquerypp.git",

// Timeouts
	stealTimeout,

// For Github credentials
	username,
	password,

// For steal build process
	pluginify;

// Get deferreds
_.mixin(require("underscore.deferred"));

function updateDist() {
	console.log("Copying built files to gh-pages.");

	var clone = spawn("git", [ "clone", remote ], {
		cwd : __dirname
	}), dfd = new _.Deferred();

	clone.on("exit", function () {

		var clonePath = path.join(__dirname, "jquerypp"),
			checkout = spawn("git", [ "checkout", "gh-pages"], {
				cwd : clonePath
			});

		checkout.on("exit", function () {
			var cloneReleasePath = path.join(clonePath, "release"),
				latestPath = path.join(cloneReleasePath, "latest"),
				versionPath = path.join(cloneReleasePath, version);
			// Make sure directories exist
			[ cloneReleasePath, versionPath, latestPath ].forEach(function (dir) {
				if (!path.existsSync(dir)) {
					fs.mkdirSync(dir);
				}
			});

			fs.readdir(distPath, function (err, files) {
				var dfds = files.map(function (file) {
					var dfd = new _.Deferred(),
						inPath, outPath, latestOutPath,
						inStream;

					if(file.match(/.*\.js/)) {
						inPath = path.join(distPath, file),
						outPath = path.join(versionPath, file),
						latestOutPath = path.join(latestPath, file),
						inStream = fs.createReadStream(inPath);
						inStream.pipe(fs.createWriteStream(outPath));
						inStream.pipe(fs.createWriteStream(latestOutPath));

						inStream.on("end", function () {
							dfd.resolve();
						});
					} else {
						dfd.resolve();
					}

					return dfd.promise();

				});

				_.when.apply(_, dfds).done(function () {
					console.log("Finished copying files. Cleaning up...")
					var add = spawn("git", ["add", "release/*"], {
						cwd : clonePath
					});

					add.on("exit", function () {
						var commit = spawn("git", ["commit", "-m", "Generated release files for " + version ], {
							cwd : clonePath
						});

						commit.on("exit", function () {
							var push = spawn("git", ["push", "origin", "gh-pages"], {
								cwd : clonePath
							});

							push.on("exit", function () {

								var remove = spawn("rm", ["-rf", "jquerypp"], {
									cwd : __dirname
								});
								console.log("Done!")

							});
						});
					});
				});
			});
		});

	});

	return dfd.promise();
}

// Upload files to the Github downloads page
function uploadFiles() {
	console.log('Uploading files');

	var dfd = new _.Deferred(),
		dfds = _.map(descriptions, function (desc, filename) {

			var dfd = new _.Deferred();

			desc = desc.replace("#{VERSION}", version);

			fs.readFile(path.join(distPath, filename), function (err, buf) {
				console.log('Uploading ' + filename + ' MIME type ' + mime.lookup(filename));

				github.httpSend({
					"user" : "jupiterjs",
					"repo" : "jquerypp",
					"name" : filename,
					"size" : buf.length,
					"description" : desc,
					"content_type" : mime.lookup(filename)
				}, {
					"url" : "/repos/:user/:repo/downloads",
					"method" : "POST",
					"params" : {
						"$user" : null,
						"$repo" : null,
						"$name" : null,
						"$size" : null,
						"description" : null,
						"$content_type" : null
					}
				}, function (err, socket) {
					console.log(err);
					var data = JSON.parse(socket.data);


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
						bucket : "github"
					}, function (e) {
						if (e) {
							console.log(e);
						}
						dfd.resolve();
					})

				});

			});

			return dfd.promise();
		});

	_.when.apply(_, dfds).done(dfd.resolve.bind(dfd));

	return dfd.promise();
}

function stealBuild() {

	var dfd = new _.Deferred(),
		zipFile;

	// Run Steal build script
	pluginify = spawn(rhinoPath + "/js", ["jquery/build/make.js"], {
		cwd : rhinoPath
	});

	pluginify.on("exit", function (code) {
		// Create a zip file
		// tar -cvzf jquery/dist/jquerypp.tar.gz jquery/dist
		zipFile = 'jquerypp-' + version + '.zip';
		descriptions[zipFile] = "jQuery++ #{VERSION} - Full download";
		spawn("zip", ["-r", zipFile, '.'], {
			cwd : distPath
		}).on("exit", function () {
			console.log("Done creating ZIP file " + zipFile);
			dfd.resolve(code);
		});
	});

	return dfd.promise();
}

// Clean up on process exit
process.on("exit", function () {
	process.stdout.write("\n")
})

// Get Github credentials
function getCredentials() {

	var dfd = new _.Deferred();

	program.prompt("Github Username: ", function (name) {
		username = name;

		program.password("Github Password: ", "*", function (pass) {
			var timeout;
			password = pass;
			process.stdin.pause();

			github.authenticate({
				type : "basic",
				username : username,
				password : password
			});

			process.stdout.write("Building jQuery++...")
			stealTimeout = setInterval(function () {
				process.stdout.write(".")
			}, 1000)
			dfd.resolve();
		})

	});

	return dfd.promise();
}

_.when(stealBuild(), getCredentials()).done(function (code) {
	console.log('Built and got credentials');

	if (stealTimeout) {
		clearTimeout(stealTimeout);
		process.stdout.write(" Done!\n")
	}

	console.log(code);
	if (code != 0) {
		console.log("Steal build process failed.")
	} else {
		_.when(uploadFiles(), updateDist()).done(function () {
		});
	}
});
