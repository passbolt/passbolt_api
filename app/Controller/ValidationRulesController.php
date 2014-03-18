<?php
/**
 * Validation Rules Controller
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.ValidationRulesController
 * @since			version 2.13.9
 */
class ValidationRulesController extends AppController {

/**
 * Get the validation rules of a target model.
 * @param $model The target model.
 * @param string $case (optional) The target case if any.
 */
	public function view($model, $case = 'default') {
		$model = ucfirst($model);
		// Get the validation settings.
		$settings = Configure::read('Validation');

		// If the validation rules can be shared with the front-end.
		if (!is_null($settings) && is_array($settings) && isset($settings['shared']) && in_array($model, $settings['shared'])) {
			App::import('Model', $model);
			$instance = new $model();
			$rules = $instance->getValidationRules($case);

			// If some rules shouldn't be shared.
			foreach ($rules as $fieldName => $fieldRules) {
				// Unique rule given.
				if (isset($fieldRules['rule'])) {
					if (isset($fieldRules['shared']) && $fieldRules['shared'] === false) {
						unset($rules[$fieldName]);
					}
				} else {
					// Multiple rules given.
					foreach ($fieldRules as $ruleName => $fieldRule) {
						if (isset($fieldRule['shared']) && $fieldRule['shared'] === false) {
							unset($rules[$fieldName][$ruleName]);
						}
					}
				}
			}

			$this->set('data', $rules);
			$this->Message->success();
			// $this->layout = 'js/canJs';
			// $this->view = '/Js/modelValidationRules';
		}
	}

}
