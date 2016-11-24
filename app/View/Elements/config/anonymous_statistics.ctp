<?php echo "<?php\n"; ?>
/**
 * Anonymous statistics configuration file
 *
 * Anonymous statistics sent are used to make passbolt better, and are only
 * related to your usage of passbolt.
 * Know more in our privacy policy: https://www.passbolt.com/privacy#statistics
 *
 * @copyright (c) 2015-present passbolt SARL.
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

$config = [
	'AnonymousStatistics' => [
        'instanceId' => '<?= $instanceId ?>',
        'send'       => <?= $send == true ? 'true' : 'false' ?>,
    ],
];
