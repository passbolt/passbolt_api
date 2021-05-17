<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 * @var \App\View\AppView $this
 */
use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

if (!isset($report)) {
    throw new InternalErrorException();
}

$this->assign('title', Purifier::clean($report['name']));
$this->Html->css('themes/default/api_reports.min.css?v='
    . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);

$subReports = $report['data'] ?? [];

foreach ($subReports as $report) {
    $reportElement = Purifier::clean($report['slug']);
    echo $this->element($reportElement, [ 'report' => $report]);
}
