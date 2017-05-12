<?php
/**
 * Validation Rules Controller
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class ValidationRulesController extends AppController {

/**
 * Get the validation rules of a target model
 *
 * @param string $model target model name
 * @param string $case (optional) the target case if any.
 * @return void
 * @throws HttpException if config is missing
 */
	public function view($model, $case = 'default') {
		$model = ucfirst($model);
		// Get the validation settings.
		$settings = Configure::read('Validation');

		// If the validation rules can be shared with the front-end.
		if (!is_null($settings)
			&& is_array($settings)
			&& isset($settings['shared'])
			&& in_array($model, $settings['shared'])
		) {
			App::import('Model', $model);
			$instance = new $model();
			$rules = $instance->getValidationRules($case);

			// If some rules shouldn't be shared.
			foreach ($rules as $fieldName => $fieldRules) {
				// Unique rule given or the exclusion of the whole field is asked.
				if (isset($fieldRules['shared']) && $fieldRules['shared'] === false) {
					unset($rules[$fieldName]);
				} elseif (!isset($fieldRules['rule'])) {
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
		} else {
			throw new HttpException(__('No validation rules defined'));
		}
	}
}
