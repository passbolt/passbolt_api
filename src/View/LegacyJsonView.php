<?php
namespace App\View;

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
        // Use same default json options than JsonView
        if (isset($this->viewVars['_jsonOptions'])) {
            if ($this->viewVars['_jsonOptions'] === false) {
                $this->viewVars['_jsonOptions'] = 0;
            }
        } else {
            $this->viewVars['_jsonOptions'] = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT |
                JSON_PARTIAL_OUTPUT_ON_ERROR;
        }

        $return = parent::render($view, $layout);

        // Enable jsonp
        if (!empty($this->viewVars['_jsonp'])) {
            $jsonpParam = $this->viewVars['_jsonp'];
            if ($this->viewVars['_jsonp'] === true) {
                $jsonpParam = 'callback';
            }
            if ($this->request->getQuery($jsonpParam)) {
                $return = sprintf('%s(%s)', h($this->request->getQuery($jsonpParam)), $return);
                $this->response->type('js');
            }
        }

        return $return;
    }
}