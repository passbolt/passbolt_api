<?php
/**
 * Profile Avatar Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('ImageStorage', 'FileStorage.Model');

class ProfileAvatar extends ImageStorage {

/**
 * Defines which key to use while returning an avatar object
 * to tell the path for the avatar image.
 * @var string
 */
/**
 * Defines which key to use while returning an avatar object
 * to tell the path for the avatar image.
 *
 * @var string
 */
	public $imagePathKey = 'url';

/**
 * Details of use table
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html
 */
	public $useTable = 'file_storage';

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $_actsAs = [
		'FileStorage.UploadValidator' => [
			'validate' => true,
			'localFile' => true,
			'allowedExtensions' => [
				'jpg', 'jpeg', 'png', 'gif'
			],
		],
	];

/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean true on success
 */
	public function beforeSave($options = []) {
		if (!parent::beforeSave($options)) {
			return false;
		}

		// delete the previous avatar
		$this->deleteAll([
			'foreign_key' => $this->data['Avatar']['foreign_key']
		]);

		return true;
	}

/**
 * After find callback.
 *
 * Is used to build an array of url for the images.
 *
 * @param mixed $results
 * @param bool $primary
 *
 * @return mixed
 */
	public function afterFind($results, $primary = false) {
		if (isset($results[0])) {
			foreach ($results as $key => $result) {
				$results[$key] = $this->addPathsInfo($result);
			}
		} else {
			$results = $this->addPathsInfo($results);
		}
		return $results;
	}

/**
 * Upload a file
 *
 * @param $foreignId
 * @param $data
 * @return mixed
 */
	public function upload($foreignId, $data) {
		// Reload behavior with our settings.
		// For some reasons, actsAs is unable to override the behavior settings
		// So we have redefined actsAs into _actsAs.
		$this->Behaviors->unload('UploadValidator');
		$this->Behaviors->load('UploadValidator', $this->_actsAs['FileStorage.UploadValidator']);

		// Check if an avatar has already been uploaded.
		$options['conditions']['Avatar.foreign_key'] = $foreignId;
		$avatar = $this->find('first', $options);

		// Build avatar data.
		$data[$this->alias]['adapter'] = 'Local';
		$data[$this->alias]['model'] = 'ProfileAvatar';
		$data[$this->alias]['extension'] = $this->fileExtension($data['Avatar']['file']['tmp_name']);
		$data[$this->alias]['foreign_key'] = $foreignId;

		$this->set($data);
		$validate = $this->validates();
		if (!$validate) {
			throw new ValidationException(__('Upload error : ') . $this->validationErrors['file'][0], ['Avatar' => $this->validationErrors]);
		}

		// If an avatar exists, delete it and its versions.
		if (!empty($avatar)) {
			// Delete the versions of the file.
			$this->id = $avatar['Avatar']['id'];
			$operations = Configure::read('Media.imageSizes.ProfileAvatar');
			$Event = new CakeEvent('ImageVersion.removeVersion', $this, [
				'record' => $avatar,
				'storage' => StorageManager::adapter('Local'),
				'operations' => $operations
			]);
			CakeEventManager::instance()->dispatch($Event);

			// Get the path of the file.
			$imagePath = $avatar['Avatar']['path'] . $this->stripUuid($avatar['Avatar']['id']) . '.' . $avatar['Avatar']['extension'];
			$fullImagePath = Configure::read('ImageStorage.basePath') . DS . $imagePath;

			// If file exists, delete it.
			if (file_exists($fullImagePath)) {
				StorageManager::adapter($avatar['Avatar']['adapter'])->delete($imagePath);
			}

			// Delete the corresponding db entry.
			$this->delete($avatar['Avatar']['id']);
		}


		$this->create();
		$save = $this->save($data);
		if (!$save) {
			throw new Exception(__('Could not save avatar'));
		}

		return true;
	}

/**
 * Get Image Url for an entry.
 *
 * @param array $image entry of the db
 * @param string $version version as defined in file storage configuration file.
 * @param array $options
 * @return bool
 */
	public function imageUrl($image, $version = null, $options = array()) {
		// Default options.
		$defaultOptions = [
			'version' => 'small',
		];
		$options = array_merge($options, $defaultOptions);

		// If image is empty, we return the default avatar.
		if (empty($image) || empty($image['id'])) {
			// Return fallback images.
			$avatarDefaults = Configure::read('Media.imageDefaults.ProfileAvatar');
			if (isset($avatarDefaults[$version])) {
				return $avatarDefaults[$version];
			}
			return false;
		}

		if (!empty($version)) {
			$hash = Configure::read('Media.imageHashes.' . $image['model'] . '.' . $version);
			if (empty($hash)) {
				throw new \InvalidArgumentException(__d('file_storage', 'No valid version key (%s %s) passed!',
					@$image['model'], $version));
			}
		} else {
			$hash = null;
		}

		$Event = new CakeEvent('FileStorage.ImageHelper.imagePath', $this, [
				'hash' => $hash,
				'image' => $image,
				'version' => $version,
				'options' => $options
			]
		);
		CakeEventManager::instance()->dispatch($Event);

		if ($Event->isStopped()) {
			return Configure::read('ImageStorage.publicPath') . $this->normalizePath($Event->data['path']);
		} else {
			return false;
		}
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


/**
 * Add avatar path information for each available size to the model.
 *
 * @param array $avatar a ProfileAvatar model
 * @return array ProfileAvatar with added information.
 */
	public function addPathsInfo($avatar = array()) {
		// Get available sizes.
		$sizes = Configure::read('Media.imageSizes.ProfileAvatar');
		$avatarsPath = [];
		// Add path for each available size.
		foreach ($sizes as $size => $filters) {
			$url = $this->imageUrl($avatar, $size);
			$avatarsPath[$size] = $url ? $url : '';
		}
		// Transform original model to add paths.
		$avatar[$this->imagePathKey] = $avatarsPath;

		return $avatar;
	}
}
