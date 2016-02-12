<?php
/**
 * Gettext Dictionary Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class DictionariesController extends AppController {

/**
 * Get the dictionary of translated strings for a given locale
 *
 * @param string $locale the name of the locale
 * @return void
 */
	public function view($locale = 'default') {
		// get user locale or application default one
		$locale = ($locale != 'default') ? $locale : Configure::read('i18n.locale');

		// find it in cache or read from model
		$cache = Cache::read('dictionary_' . $locale, '_cake_model_');
		if ($cache === false) {
			$data = $this->Dictionary->get($locale);
			if ($data) {
				Cache::write('dictionary_' . $locale, $data, '_cake_model_');
			}
		} else {
			$data = $cache;
		}

		if ($data) {
			$this->set('data', $data);
			$this->Message->success();
		} else {
			$this->Message->error(__('Sorry the dictionary could not be found'));
		}
	}

}
