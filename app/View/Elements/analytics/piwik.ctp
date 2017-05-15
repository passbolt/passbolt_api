<?php
/**
 * Piwik Element
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$analytics = Configure::read('Analytics');
$analyticsConfigured = isset($analytics['piwik']) && !empty($analytics['piwik'])
	&& isset($analytics['piwik']['url']) && !empty($analytics['piwik']['url']);
$trackingOn = !isset($do_not_track) || isset($do_not_track) && $do_not_track == false;
$debug = Configure::read('debug');
if ($trackingOn && $analyticsConfigured && !$debug) :
	$piwikUrl = Configure::read('Analytics.piwik.url');
?>
<!-- Piwik Analytics -->
<script type="text/javascript">
	var _paq = _paq || [];
	_paq.push(['trackPageView']);
	_paq.push(['enableLinkTracking']);
	(function() {
		var u="//<?php echo $piwikUrl ?>/";
		_paq.push(['setTrackerUrl', u+'piwik.php']);
		_paq.push(['setSiteId', 1]);
		var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
	})();
</script>
<noscript><p><img src="//<?php echo $piwikUrl ?>/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Analytics Code -->
<?php endif; ?>
