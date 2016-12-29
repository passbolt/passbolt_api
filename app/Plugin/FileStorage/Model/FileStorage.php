<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('FileStorageAppModel', 'FileStorage.Model');
App::uses('StorageManager', 'FileStorage.Lib');
App::uses('FileStorageUtils', 'FileStorage.Utility');
/**
 * FileStorage
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class FileStorage extends FileStorageAppModel {

/**
 * Name
 *
 * @var string
 */
	public $name = 'FileStorage';

/**
 * Table name
 *
 * @var string
 */
	public $useTable = 'file_storage';

/**
 * Displayfield
 *
 * @var string
 */
	public $displayField = 'filename';

/**
 * The record that was deleted
 *
 * This gets set in the beforeDelete() callback so that the data is available
 * in the afterDelete() callback
 *
 * @var array
 */
	public $record = array();

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'adapter' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
			)
		),
		'path' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
			)
		),
		'foreign_key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
			)
		),
		'model' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
			)
		)
	);

/**
 * List of dynamically added validation rules in beforeValidate() that are going
 * to be unset in afterValidate().
 *
 * @var array
 */
	protected $_temporaryValidationRules = array();

/**
 * Renews the FileUpload behavior with a new configuration
 *
 * @param array $options
 * @return void
 */
	public function configureUploadValidation($options) {
		$this->Behaviors->unload('FileStorage.UploadValidator');
		$this->Behaviors->load('FileStorage.UploadValidator', $options);
	}

/**
 * beforeValidate callback
 *
 * @param array $options
 * @return boolean
 */
	public function beforeValidate($options = array()) {
		if (!parent::beforeValidate($options)) {
			return false;
		}
		if (isset($this->data[$this->alias]['model'])) {
			$method = 'validate' . Inflector::camelize($this->data[$this->alias]['model']);
			if (method_exists($this, $method) || $this->Behaviors->hasMethod($method)) {
				$this->{$method}($options);
			}
		}
		return true;
	}

/**
 * afterValidate callback
 *
 * @return void
 */
	public function afterValidate() {
		parent::afterValidate();
		if (!empty($this->_temporaryValidationRules)) {
			foreach ($this->_temporaryValidationRules as $field => $rules) {
				foreach ($rules as $rule) {
					unset($this->validate[$field][$rule]);
				}
			}
		}
	}

/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean true on success
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['file']['tmp_name'])) {
			$File = new File($this->data[$this->alias]['file']['tmp_name']);
			$this->data[$this->alias]['filesize'] = $File->size();
			$this->data[$this->alias]['mime_type'] = $File->mime();
		}
		if (!empty($this->data[$this->alias]['file']['name'])) {
			$this->data[$this->alias]['extension'] = $this->fileExtension($this->data[$this->alias]['file']['name']);
			$this->data[$this->alias]['filename'] = $this->data[$this->alias]['file']['name'];
		}

		if (empty($this->data[$this->alias]['adapter'])) {
			$this->data[$this->alias]['adapter'] = 'Local';
		}

		$Event = new CakeEvent('FileStorage.beforeSave', $this, array(
			'record' => $this->data,
			'storage' => $this->getStorageAdapter($this->data[$this->alias]['adapter'])));
		$this->getEventManager()->dispatch($Event);
		if ($Event->isStopped()) {
			return false;
		}

		return true;
	}

/**
 * afterSave callback
 *
 * @param boolean $created
 * @param array $options
 * @return void
 */
	public function afterSave($created, $options = array()) {
		if ($created) {
			$this->data[$this->alias][$this->primaryKey] = $this->getLastInsertId();
		}

		$Event = new CakeEvent('FileStorage.afterSave', $this, array(
			'created' => $created,
			//'record' => $this->record,
			'record' => $this->data,
			'storage' => $this->getStorageAdapter($this->data[$this->alias]['adapter'])));
		$this->getEventManager()->dispatch($Event);

		$this->deleteOldFileOnSave();

		return true;
	}

/**
 * Get a copy of the actual record before we delete it to have it present in afterDelete
 *
 * @param boolean $cascade
 * @return boolean
 */
	public function beforeDelete($cascade = true) {
		if (!parent::beforeDelete($cascade)) {
			return false;
		}

		$this->record = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $this->id)));

		if (empty($this->record)) {
			return false;
		}

		return true;
	}

/**
 * afterDelete callback
 *
 * @return mixed
 */
	public function afterDelete() {
		try {
			$Storage = $this->getStorageAdapter($this->record[$this->alias]['adapter']);
			$Storage->delete($this->record[$this->alias]['path']);
		} catch (Exception $e) {
			$this->log($e->getMessage(), 'file_storage');
			return false;
		}

		$Event = new CakeEvent('FileStorage.afterDelete', $this, array(
			'record' => $this->record,
			'storage' => $this->getStorageAdapter($this->record[$this->alias]['adapter'])));
		$this->getEventManager()->dispatch($Event);

		return true;
	}

/**
 * Creates a tmp file name and checks the tmp path, creates one if required
 *
 * This method is thought to be used to generate tmp file locations for use cases
 * like audio or image process were you need copies of a file and want to avoid
 * conflicts. By default the tmp file is generated using cakes TMP constant +
 * folder if passed and a uuid as filename.
 *
 * @param string $folder
 * @param boolean $checkAndCreatePath
 * @return string For example /var/www/app/tmp/<uuid> or /var/www/app/tmp/<my-folder>/<uuid>
 */
	public function tmpFile($folder = null, $checkAndCreatePath = true) {
		if (empty($folder)) {
			$path = TMP;
		} else {
			$path = TMP . $folder . DS;
		}

		if ($checkAndCreatePath === true && !is_dir($path)) {
			new Folder($path, true);
		}

		return $path . String::uuid();
	}

/**
 * Removes the - from the uuid
 *
 * @param string uuid with -
 * @return string uuid without -
 */
	public function stripUuid($uuid) {
		return str_replace('-', '', $uuid);
	}

/**
 * Generates a semi-random file system path
 *
 * @param string $type
 * @param string $string
 * @param boolean $idFolder
 * @return string
 */
	public function fsPath($type, $string, $idFolder = true) {
		$string = str_replace('-', '', $string);
		$path = $type . DS . FileStorageUtils::randomPath($string);
		if ($idFolder) {
			$path .= $string . DS;
		}
		return $path;
	}

/**
 * Return file extension from a given filename no matter if the file exists or not
 *
 * @param string
 * @return boolean string or false
 */
	public function fileExtension($path) {
		if (file_exists($path)) {
			return pathinfo($path, PATHINFO_EXTENSION);
		} else {
			return substr(strrchr($path,'.'), 1);
		}
	}

/**
 * Get a storage adapter from the StorageManager
 *
 * @param string $adapterName
 * @param boolean $renewObject
 * @return \Gaufrette\Adapter
 */
	public function getStorageAdapter($adapterName, $renewObject = false) {
		return StorageManager::adapter($adapterName, $renewObject);
	}

/**
 * Deletes an old file to replace it with the new one if an old id was passed.
 *
 * Thought to be called in Model::afterSave() but can be used from any other
 * place as well like Model::beforeSave() as long as the field data is present.
 *
 * The old id has to be the UUID of the file_storage record that should be deleted.
 *
 * @param string $oldIdField Name of the field in the data that holds the old id
 * @return boolean Returns true if the old record was deleted
 */
	public function deleteOldFileOnSave($oldIdField = 'old_file_id') {
		if (!empty($this->data[$this->alias][$oldIdField]) && $this->data[$this->alias]['model']) {
			return $this->delete($this->data[$this->alias][$oldIdField]);
		}
		return false;
	}
}
