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
 * @since         2.14.0
 */
namespace App\Shell\Task;

use App\Service\Gpgkeys\GpgkeysHealthcheckService;
use App\Shell\AppShell;
use App\Utility\Healthchecks\Healthcheck;
use Cake\Core\Exception\Exception;
use Cake\Utility\Hash;

class DatacheckTask extends AppShell
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        // Display options
        $parser->setDescription(__('Re-validate the data of this installation.'))
            ->addOption('hide-success-details', [
            'help' => __d('cake_console', 'Hide passing checks details.'),
            'boolean' => true,
            ])
            ->addOption('hide-error-details', [
                'help' => __d('cake_console', 'Hide passing checks details.'),
                'boolean' => true,
            ]);
        return $parser;
    }

    /**
     * Assert all the checks
     *
     * @return bool
     */
    public function main()
    {
        $this->out('Data check shell');

        $services[] = new GpgkeysHealthcheckService();
        foreach($services as $i => $service) {
            try {
                $results = $service->check();
                $this->displayResults($results, $service->getServiceCategory(), $service->getServiceName());
            } catch (\Exception $exception) {
                $this->out($exception->getMessage());
            }
        }
        return true;
    }

    /**
     * @param array $results checks results
     * @param string $serviceCategory healthcheck service category ('data')
     */
    protected function displayResults(array $results, string $serviceCategory, string $serviceName) {
        $results = $results[$serviceCategory];
        $this->displayServiceTotal($results, $serviceName);
        foreach($results as $name => $subresults) {
            $details = $subresults->getDetails();
            $this->displayCheckTotal($details, $name);
            $this->displayCheckDetails($details);
        }
    }

    protected function displayServiceTotal(array $results, string $serviceName) {
        $status = (count(Hash::extract($results, '{s}.{s}.{n}[status=error]'))) ? Healthcheck::STATUS_SUCCESS : Healthcheck::STATUS_ERROR;
        $this->display(__('Data integrity for {0}.', $serviceName), $status);
    }

    /**
     * @param array $details
     * @param $name
     */
    protected function displayCheckTotal(array $details, $name) {
        $success = count(Hash::extract($details, '{n}[status=success]'));
        $total = count($details);
        $msg = $name . ': ' . $success . '/' . $total;
        $status = ($success === $total) ? Healthcheck::STATUS_SUCCESS: Healthcheck::STATUS_ERROR;
        $this->display($msg, $status, 2);
    }

    /**
     * @param array $details results details
     */
    protected function displayCheckDetails(array $details) {
        $count = 0;
        foreach ($details as $detail) {
            if ((!$this->param('hide-success-details') && $detail['status'] === Healthcheck::STATUS_SUCCESS) ||
                (!$this->param('hide-error-details') && $detail['status'] === Healthcheck::STATUS_ERROR)
            ) {
                $count++;
                $this->display($detail['message'], $detail['status'], 4);
            }
        }
        if ($count) {
            $this->out();
        }
    }

    /**
     * Display a message for given case
     *
     * @param string $msg message
     * @param string $case pass or fail
     * @throws Exception case is not defined or missing
     * @return void
     */
    protected function display($msg, $case, $padding = 0)
    {
        $pad = '';
        for($i=0; $i < $padding; $i++) {
         $pad .= ' ';
        }

        switch ($case) {
            case Healthcheck::STATUS_SUCCESS:
                $msg = $pad . '<success>[' . __('PASS') . ']</success> ' . $msg;
                break;
            case Healthcheck::STATUS_ERROR:
                $msg = $pad . '<fail>[' . __('FAIL') . '] ' . $msg . '</fail>';
                break;
            case Healthcheck::STATUS_WARNING:
                $msg = $pad . '<warning>[' . __('WARN') . '] ' . $msg . '</warning>';
                break;
            case Healthcheck::STATUS_INFO:
                $msg = $pad . ' <info>[' . __('HELP') . ']</info> ' . $msg;
                break;
            default:
                throw new Exception('Task output case not defined: ' . $case . ' ' . $msg);
        }
        $this->out($msg);
    }
}
