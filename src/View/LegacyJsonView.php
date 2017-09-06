<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\View;

use Cake\View\View;
use Cake\Core\Configure;
use App\View\Helper\LegacyApiHelper;

class LegacyJsonView extends View
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Render a JSON view.
     *
     * ### Special parameters
     * `_serialize` To convert a set of view variables into a JSON response.
     *   Its value can be a string for single variable name or array for multiple
     *   names. If true all view variables will be serialized. It unset normal
     *   view template will be rendered.
     * `_jsonp` Enables JSONP support and wraps response in callback function
     *   provided in query string.
     *   - Setting it to true enables the default query string parameter "callback".
     *   - Setting it to a string value, uses the provided query string parameter
     *     for finding the JSONP callback name.
     * `_jsonOptions` You can set custom options for json_encode() this way,
     *   e.g. `JSON_HEX_TAG | JSON_HEX_APOS`.
     *
     * @param string|null $view The view being rendered.
     * @param string|null $layout The layout being rendered.
     * @return string|null The rendered view.
     */
    public function render($view = null, $layout = null)
    {
        $jsonOptions = self::getJsonOptions();

        // format body object (result set expected)
        if (isset($this->viewVars['body'])) {
            $body = LegacyApiHelper::formatResultSet($this->viewVars['body']);
        }

        // if no view is selected, encode and return
        if (!isset($view)) {
            return json_encode(['header' => $this->viewVars['header'], 'body' => $body], $jsonOptions);
        } else {
            $this->viewVars['body'] = $body;
            $this->viewVars['_jsonOptions'] = $jsonOptions;
            return parent::render($view, $layout);
        }
    }

    /**
     * Get Json Options
     * Build them from view vars or fall back to defaults
     *
     * @return int
     */
    protected function getJsonOptions() {
        // Use same default json options than JsonView
        if (isset($this->viewVars['_jsonOptions'])) {
            if ($this->viewVars['_jsonOptions'] === false) {
                $jsonOptions = 0;
            } else {
                $jsonOptions = $this->viewVars['_jsonOptions'];
            }
        } else {
            $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT |
                JSON_PARTIAL_OUTPUT_ON_ERROR;
        }
        if (Configure::read('debug')) {
            $jsonOptions |= JSON_PRETTY_PRINT;
        }
        return $jsonOptions;
    }
}