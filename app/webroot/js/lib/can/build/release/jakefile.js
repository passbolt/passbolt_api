var path = require("path"),
	spawn = require("child_process").spawn,
	rootPath = path.join(__dirname, "../../../"),
	exec = function (cmd, args, callback, options) {
		console.log(cmd + ' ' + args.join(' '));
		var spawned = spawn(cmd, args, options || {});

		spawned.stdout.pipe(process.stdout, { end : false });
		spawned.stderr.pipe(process.stderr, { end : false });

		spawned.on('exit', function () {
			callback();
		});
	};

desc('Runs make.js to build edge');
task('build', function (params) {
	var executable = process.platform == 'win32' ? 'js.bat' : './js';
	console.log('Building...');
	exec(executable, ['can/util/make.js'], function () {
		complete();
	}, { cwd : rootPath });
}, { async : true });

namespace('deploy', function () {
	desc('Checkout gh-pages branch and update latest release');
	task('edge', [ 'build' ], function () {
		console.log('Cloning CanUI repository');
		exec('git', ['clone', 'git@github.com:jupiterjs/canjs.git'], function () {
			exec('git', ['checkout', 'gh-pages'], function() {
				jake.cpR(path.join(rootPath + 'can/dist/edge/'), './canjs/release/');
				exec('git', ['add', '.', '--all'], function() {
					exec('git', ['commit', '-m', '"Updating edge"'], function() {
						exec('git', ['push', 'origin', 'gh-pages'], function () {
							exec('rm', [ '-rf', './canjs' ], function() {});
						}, { cwd : './canjs' });
					}, { cwd : './canjs' });
				}, { cwd : './canjs' });
				complete();
			}, { cwd : './canjs' });
		});
	}, { async : true });
});
