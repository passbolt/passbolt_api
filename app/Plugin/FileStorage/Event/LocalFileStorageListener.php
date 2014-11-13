<?php
App::uses('CakeEventListener', 'Event');
/**
 * Local FileStorage Event Listener for the CakePHP FileStorage plugin
 *
 * @author Tomenko Yegeny
 * @license MIT
 */
class LocalFileStorageListener extends Object implements CakeEventListener {

/**
 * Implemented Events
 *
 * @return array
 */
	public function implementedEvents() {
		return array(
			'FileStorage.afterSave' => 'afterSave',
			'FileStorage.afterDelete' => 'afterDelete',
		);
	}

/**
 * afterDelete
 *
 * No need to use an adapter here, just delete the whole folder using cakes Folder class
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function afterDelete(CakeEvent $Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$path = Configure::read('Media.basePath') . $Event->data['record'][$Model->alias]['path'];
			if (is_dir($path)) {
				$Folder = new Folder($path);
				return $Folder->delete();
			}
			return false;
		}
	}

/**
 * afterSave
 *
 * @param CakeEvent $Event
 * @return void
 */
	public function afterSave(CakeEvent $Event) {
		if ($this->_checkEvent($Event)) {
			$Model = $Event->subject();
			$record = $Model->data[$Model->alias];
			$Storage = StorageManager::adapter($record['adapter']);

			try {
				$id = $record[$Model->primaryKey];
				$filename = $Model->stripUuid($id);
				$file = $record['file'];
				$record['path'] = $Model->fsPath('files' . DS . $record['model'], $id);
				$result = $Storage->write($record['path'] . $filename . '.' . $record['extension'], file_get_contents($file['tmp_name']), true);

				$Model->save(array($Model->alias => $record), array(
					'validate' => false,
					'callbacks' => false
				));

			} catch (Exception $e) {
				$this->log($e->getMessage(), 'file_storage');
			}
		}
	}

/**
 * Check if the event is of a type / model we want to process with this listener
 *
 * @param CakeEvent $Event
 * @return boolean
 */
	protected function _checkEvent($Event) {
		$Model = $Event->subject();
		return ($Model instanceOf FileStorage && ((isset($Event->data['record'][$Model->alias]['adapter']) && $Event->data['record'][$Model->alias]['adapter'] == 'Local') || get_class($Event->data['storage']->getAdapter()) == 'Gaufrette\Adapter\Local'));
	}

}