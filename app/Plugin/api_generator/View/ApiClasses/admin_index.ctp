<?php $this->Html->script('/api_generator/js/request_manager.js', array('inline' => false)); ?>
<h1><?php echo __d('api_generator', 'Admin Class Index'); ?></h1>
<table class="listing coverage" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort(__d('api_generator', 'Classname'), 'name'); ?> </th>
			<th><?php echo $this->Paginator->sort(__d('api_generator', 'Coverage'), 'coverage_cache'); ?>
			<th><?php echo __d('api_generator', 'Actions'); ?> </th>
		</tr>
	</thead>
	<?php foreach ($apiClasses as $apiClass): ?>
		<tr>
			<td><?php echo $apiClass['ApiClass']['name']; ?></td>
			<td><?php 
				if (!empty($apiClass['ApiClass']['coverage_cache'])): 
					echo $this->ApiUtils->colourPercent($apiClass['ApiClass']['coverage_cache']);
				else:
					echo '<span class="coverage-indicator" id="' . $apiClass['ApiClass']['id'] . '">' . __d('api_generator', 'Loading..') . '</span>';
				endif;
			?></td>
			<td>
				<?php 
				echo $this->Html->link(__d('api_generator', 'View Coverage'), array('action' => 'docs_coverage', $apiClass['ApiClass']['slug'])); 
				?>
			</td>
		</tr>
	<?php endforeach ?>
</table>
<?php echo $this->element('paging'); ?>

<script type="text/javascript">
if (window.basePath === undefined) {
	window.basePath = '<?php echo $this->Html->webroot('/'); ?>';
}
ApiGenerator.classIndex = {
	coverageUrl : window.basePath + '/admin/api_generator/api_classes/calculate_coverage/',

	init : function () {
		var self = this;

		$$('table.listing .coverage-indicator').each(function (item, i) {
			var requestOpts = {
				url : self.coverageUrl + item.get('id'),
				onSuccess : self.updateCoverage,
				method : 'get'
			};
			RequestManager.prefetch(requestOpts);
		});
	},

	updateCoverage : function (responseText, responseXml) {
		var object = JSON.decode(responseText);
		var value = Math.round(object.coverage * 100) / 100;
		$(object.id).set('text', value + '%');
		value = Math.round(value);
		if (value >= 75) {
			$(object.id).setStyle('color', 'green');
		} else if (value < 75 && value > 50) {
			$(object.id).setStyle('color', 'GoldenRod');
		} else {
			$(object.id).setStyle('color', 'DarkRed');
		}
	}
};
</script>