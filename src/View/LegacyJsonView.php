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
 * @since         2.0.0
 */
namespace App\View;

use App\View\Helper\LegacyApiHelper;
use Cake\Core\Configure;
use Cake\View\View;

class LegacyJsonView extends View
{
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
        $body = $this->viewVars['body'];

        if (isset($body)) {
            if (isset($this->viewVars['error'])) {
                $error = $this->viewVars['error'];
                if (method_exists($error, 'getTable')) {
                    $table = $error->getTable();
                    $body = LegacyApiHelper::formatErrors($body, $table);
                } else {
                    $body = '';
                }
            } else {
                // What kind of body are we dealing with
                $isEntity = (get_parent_class($body) === 'Cake\ORM\Entity');
                $isQuery = ($body instanceof \Cake\ORM\Query);
                $isCollection = ($body instanceof \Cake\Collection\Collection);

                // Format based on the expected format for each types
                if ($isEntity) {
                    $name = LegacyApiHelper::getEntityName($body);
                    $body = LegacyApiHelper::formatEntity($body, $name);
                } elseif ($isQuery || $isCollection) {
                    $body = LegacyApiHelper::formatResultSet($body);
                }
            }
        }

        return json_encode(['header' => $this->viewVars['header'], 'body' => $body], $jsonOptions);
    }

    /**
     * Get Json Options
     * Build them from view vars or fall back to defaults
     *
     * @return int
     */
    protected function getJsonOptions()
    {
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
