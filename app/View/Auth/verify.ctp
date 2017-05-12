<?php
/**
 * Verify View
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
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