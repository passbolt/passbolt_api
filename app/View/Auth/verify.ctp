<?php
    $this->assign('title',	__('Server public key'));
    $this->assign('page_classes', 'auth verify');
    if (isset($data)) :
?>
<pre>
<div id="fingerprint">
<?php echo $data['fingerprint']; ?>
</div>
<div class="data">
<?php echo $data['keydata']; ?>
</div>
</pre>
<?php endif; ?>