<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h2><?php echo __d('migrations', 'Migration Status') ?></h2>
<div class="code-table">

	<a id="hide-migrations" href="#migrations">Toggle Applied migrations</a>

	<?php
	foreach ($content as $type => $migration) {
		if (empty($migration['map'])) {
			continue;
		}
		$migration = array_reverse($migration);
		?>
		<br/>
		<h4><?php echo Inflector::humanize($type); ?></h4>
		<?php
		foreach ($migration['map'] as $index => $info) {
			if (empty($info['migrated'])) {
				$status = array(
					'color' => '#fcc',
					'image' => 'http://cakephp.org/img/test-fail-icon.png');
			} else {
				$status = array(
					'color' => '#cfc',
					'image' => 'http://cakephp.org/img/test-pass-icon.png');
			}
			?>
			<div class="migration-<?php echo empty($info['migrated']) ? 'unapplied' : 'applied';?>" style="background: <?php echo $status['color']; ?> url(<?php echo $status['image']; ?>) 5px 3px no-repeat; border-bottom: solid #ccc 1px; line-height: 1.75em; padding-left: 27px;">
				<?php echo sprintf('[%s] %s', $info['version'], $info['name']); ?>
			</div>
			<?php
		}
	}
	?>
</div>
<script type="text/javascript">
//<![CDATA[

if (document.getElementsByClassName == undefined) {
	document.getElementsByClassName = function(className) {
		var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
		var allElements = document.getElementsByTagName("*");
		var results = [];

		var element;
		for (var i = 0; (element = allElements[i]) != null; i++) {
			var elementClass = element.className;
			if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
				results.push(element);
		}

		return results;
	}
}
setTimeout(function() {
    DEBUGKIT.$(document).ready(function () {
        DEBUGKIT.module('migrationsPanel');
        DEBUGKIT.migrationsPanel = function () {
            var toolbar = DEBUGKIT.toolbar,
                Element = DEBUGKIT.Util.Element,
                Cookie = DEBUGKIT.Util.Cookie,
                Collection = DEBUGKIT.Util.Collection,
                Event = DEBUGKIT.Util.Event,
                migrationsHidden = false;

            return {
                init: function () {
                    var button = document.getElementById('hide-migrations'),
                        self = this;

                    Event.addEvent(button, 'click', function (event) {
                        event.preventDefault();
                        self.toggleMigrations();
                    });

                    var migrationsState = Cookie.read('migrationsDisplay');
                    console.log(migrationsState);
                    if (migrationsState != 'show') {
                        migrationsHidden = false;
                        this.toggleMigrations();
                    }
                },

                toggleMigrations: function () {
                    var display = migrationsHidden ? 'show' : 'hide';
                    var arr = document.getElementsByClassName("migration-applied");
                    for (i = 0; i < arr.length; i++) {
                        Element[display](arr[i]);
                    }
                    Cookie.write('migrationsDisplay', display);
                    migrationsHidden = !migrationsHidden;
                    return false;
                }
            };
        }();
        DEBUGKIT.loader.register(DEBUGKIT.migrationsPanel);
    });
}, 0);
//]]>
</script>
