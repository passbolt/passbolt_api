<?php
/**
 * SQL Trace Debug Element
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<div id="js-devel-sql-trace" class="devel-sql-trace grid">
	<div class="row">
		<div class="col12">
			<?php echo $this->element('sql_dump')."\n"; ?>
		</div>
	</div>
</div>
