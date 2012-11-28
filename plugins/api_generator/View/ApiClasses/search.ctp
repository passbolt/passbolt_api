<?php
/**
 * Api Search results
 *
 */
$this->ApiDoc->setClassIndex($classIndex);
?>
<h1><?php echo sprintf(__d('api_generator', 'Search Results for "%s"'), $this->passedArgs[0]); ?></h1>
<?php if (empty($docs)): ?>
	<p class="error"><?php echo __d('api_generator', 'Your search returned no results'); ?></p>
<?php return; 
endif; ?>

<ul id="search-results">
<?php foreach ($docs as $result):
	if (isset($result['function'])):
		foreach($result['function'] as $name => $doc): ?>
			<li class="doc-block function-info">
				<h2><?php echo $this->ApiDoc->fileLink($doc->info['declaredInFile']); ?></h2>
				<div class="access public">
				<?php
					echo $this->Html->link($doc->info['signature'],
						array('action' => 'view_file', $this->ApiDoc->trimFileName($doc->info['declaredInFile']),
						'#' => 'function-' . $doc->name),
						array('class' => 'scroll-link')
					);
				?>
				</div>
			</li>
<?php	endforeach;
	elseif (isset($result['class'])) :
		foreach ($result['class'] as $name => $doc): ?>
			<li>
				<h2><?php echo $this->ApiDoc->classLink($doc->name, array(), array('class' => false)); ?></h2><?php
			if ($doc->properties):
				echo $this->element('properties', array('doc' => $doc, 'isSearch' => true));
			endif;
		
			if ($doc->methods):
				echo $this->element('method_summary', array('doc' => $doc, 'isSearch' => true));
			endif;
	?>
			</li>
	<?php	
		endforeach;
	endif;
endforeach;
?>
</ul>