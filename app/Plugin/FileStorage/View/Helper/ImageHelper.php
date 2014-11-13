<?php
/**
 * ImageHelper
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class ImageHelper extends AppHelper {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array(
		'Html'
	);

/**
 * Generates an image url based on the image record data and the used Gaufrette adapter to store it
 *
 * @param array $image FileStorage array record or whatever else table that matches this helpers needs without the model, we just want the record fields
 * @param string $version Image version string
 * @param array $options HtmlHelper::image(), 2nd arg options array
 * @return string
 */
	public function display($image, $version = null, $options = array()) {
		$url = $this->imageUrl($image, $version, $options);

		if ($url !== false) {
			return $this->Html->image($url, $options);
		}

		return $this->fallbackImage($options, $image, $version);
	}

/**
 * URL
 *
 * @param array $image FileStorage array record or whatever else table that matches this helpers needs without the model, we just want the record fields
 * @param string $version Image version string
 * @param array $options HtmlHelper::image(), 2nd arg options array
 * @throws InvalidArgumentException
 * @return string
 */
	public function imageUrl($image, $version = null, $options = array()) {
		if (empty($image) || empty($image['id'])) {
			return false;
		}

		if (!empty($version)) {
			$hash = Configure::read('Media.imageHashes.' . $image['model'] . '.' . $version);
			if (empty($hash)) {
				throw new \InvalidArgumentException(__d('file_storage', 'No valid version key (%s %s) passed!', @$image['model'], $version));
			}
		} else {
			$hash = null;
		}

		$Event = new CakeEvent('FileStorage.ImageHelper.imagePath', $this, array(
				'hash' => $hash,
				'image' => $image,
				'version' => $version,
				'options' => $options
			)
		);
		CakeEventManager::instance()->dispatch($Event);

		if ($Event->isStopped()) {
			return $this->normalizePath($Event->data['path']);
		} else {
			return false;
		}
	}

/**
 * Provides a fallback image if the image record is empty
 *
 * @param array $options
 * @param array $image
 * @param string $version
 * @return string
 */
	public function fallbackImage($options = array(), $image = array(), $version = null) {
		if (isset($options['fallback'])) {
			if ($options['fallback'] === true) {
				$imageFile = 'placeholder/' . $version . '.jpg';
			} else {
				$imageFile = $options['fallback'];
			}
			unset($options['fallback']);
			return $this->Html->image($imageFile, $options);
		}
		return '';
	}

/**
 * Turns the windows \ into / so that the path can be used in an url
 *
 * @param string $path
 * @return string
 */
	public function normalizePath($path) {
		return str_replace('\\', '/', $path);
	}

}