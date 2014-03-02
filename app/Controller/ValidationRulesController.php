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
 * @param $model
 */
	public function view($model, $case = 'default') {
		$model = ucfirst($model);
		// Get the validation settings.
		$settings = Configure::read('Validation');

		// If the validation rules can be shared with the front-end.
		if (!is_null($settings) && is_array($settings) && isset($settings['shared']) && in_array($model, $settings['shared'])) {
			App::import('Model', $model);
			$instance = new $model();
			$this->set('data', $instance->getValidationRules($case));
			$this->Message->success();
			// $this->layout = 'js/canJs';
			// $this->view = '/Js/modelValidationRules';
		}
	}

}
