<?php 
/**
 * Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Utils Plugin
 *
 * Utils Soft Delete Behavior
 *
 * @package utils
 * @subpackage utils.models.behaviors
 */
class SoftDeleteBehavior extends ModelBehavior {

/**
 * Default settings
 *
 * @var array $default
 */
	public $default = array('deleted' => 'deleted_date');

/**
 * Holds activity flags for models
 *
 * @var array $runtime
 */
	public $runtime = array();

/**
 * Setup callback
 *
 * @param object $model
 * @param array $settings
 */
    public function setup($model, $settings = array()) {
        if (empty($settings)) {
            $settings = $this->default;
        } elseif (!is_array($settings)) {
            $settings = array($settings);
        }

        $error = 'SoftDeleteBehavior::setup(): model ' . $model->alias . ' has no field ';
        $fields = $this->_normalizeFields($model, $settings);
				$model->hasField('deleted');
        foreach ($fields as $flag => $date) {
            if ($model->hasField($flag)) {
                if ($date && !$model->hasField($date)) {
                    trigger_error($error . $date, E_USER_NOTICE);
                    return;
                }
                continue;
            }
            trigger_error($error . $flag, E_USER_NOTICE);
            return;
        }

        $this->settings[$model->alias] = $fields;
        $this->softDelete($model, true);
    }

/**
 * Before find callback
 *
 * @param object $model
 * @param array $query
 * @return array
 */
    public function beforeFind($model, $query) {
        /*$runtime = $this->runtime[$model->alias];
        if ($runtime) {
			if (!is_array($query['conditions'])) {
				$query['conditions'] = array();
			}
            $conditions = array_filter(array_keys($query['conditions']));

            $fields = $this->_normalizeFields($model);

            foreach ($fields as $flag => $date) {
                if (true === $runtime || $flag === $runtime) {
                    if (!in_array($flag, $conditions) && !in_array($model->name . '.' . $flag, $conditions)) {
                        $query['conditions'][$model->alias . '.' . $flag] = false;
                    }

                    if ($flag === $runtime) {
                        break;
                    }
                }
            }
            return $query;
        }*/
    }

/**
 * Before delete callback
 *
 * @param object $model
 * @param array $query
 * @return boolean
 */
    public function beforeDelete($model) {
        $runtime = $this->runtime[$model->alias];
        if ($runtime) {
        	$res = $this->delete($model, $model->id);
            return false;
        } else {
			return true;
        }
    }

/**
 * Mark record as deleted
 *
 * @param object $model
 * @param integer $id
 * @return boolean
 */
	public function delete($model, $id) {
		$runtime = $this->runtime[$model->alias];

		$data = array();
		$fields = $this->_normalizeFields($model);
		foreach ($fields as $flag => $date) {
			if (true === $runtime || $flag === $runtime) {
				$data[$flag] = true;
				if ($date) {
					$data[$date] = date('Y-m-d H:i:s');
				}
				if ($flag === $runtime) {
					break;
				}
			}
		}

		$model->create();
		$model->set($model->primaryKey, $id);
		return $model->save(array($model->alias => $data), false, array_keys($data));
	}

/**
 * Mark record as not deleted
 *
 * @param object $model
 * @param integer $id
 * @return boolean
 */
	public function undelete($model, $id) {
		$runtime = $this->runtime[$model->alias];
		$this->softDelete($model, false);

		$data = array();
		$fields = $this->_normalizeFields($model);
		foreach ($fields as $flag => $date) {
			if (true === $runtime || $flag === $runtime) {
				$data[$flag] = false;
				if ($date) {
					$data[$date] = null;
				}
				if ($flag === $runtime) {
					break;
				}
			}
		}

		$model->create();
		$model->set($model->primaryKey, $id);
		$result = $model->save(array($model->alias => $data), false, array_keys($data));
		$this->softDelete($model, $runtime);
		return $result;
	}

/**
 * Enable/disable SoftDelete functionality
 *
 * Usage from model:
 * $this->softDelete(false); deactivate this behavior for model
 * $this->softDelete('field_two'); enabled only for this flag field
 * $this->softDelete(true); enable again for all flag fields
 * $config = $this->softDelete(null); for obtaining current setting
 *
 * @param object $model
 * @param mixed $active
 * @return mixed if $active is null, then current setting/null, or boolean if runtime setting for model was changed
 */
	public function softDelete($model, $active) {
		if (is_null($active)) {
			return isset($this->runtime[$model->alias]) ? @$this->runtime[$model->alias] : null;
		}


		$result = !isset($this->runtime[$model->alias]) || $this->runtime[$model->alias] !== $active;
		$this->runtime[$model->alias] = $active;
		$this->_softDeleteAssociations($model, $active);
		return $result;
	}

/**
 * Returns number of outdated softdeleted records prepared for purge
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return integer
 */
    public function purgeDeletedCount($model, $expiration = '-90 days') {
        $this->softDelete($model, false);
        return $model->find('count', array(
			'conditions' => $this->_purgeDeletedConditions($model, $expiration), 
			'recursive' => -1));
    }

/**
 * Purge table
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return boolean if there were some outdated records
 */
    public function purgeDeleted($model, $expiration = '-90 days') {
        $this->softDelete($model, false);
        $records = $model->find('all', array(
			'conditions' => $this->_purgeDeletedConditions($model, $expiration), 
			'fields' => array($model->primaryKey), 
			'recursive' => -1));
        if ($records) {
            foreach ($records as $record) {
                $model->delete($record[$model->alias][$model->primaryKey]);
            }
            return true;
        }
        return false;
    }

/**
 * Returns conditions for finding outdated records
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return array
 */
    protected function _purgeDeletedConditions($model, $expiration = '-90 days') {
        $purgeDate = date('Y-m-d H:i:s', strtotime($expiration));
        $conditions = array();
        foreach ($this->settings[$model->alias] as $flag => $date) {
            $conditions[$model->alias . '.' . $flag] = true;
            if ($date) {
                $conditions[$model->alias . '.' . $date . ' <'] =  $purgeDate;
            }
        }
        return $conditions;
    }

/**
 * Return normalized field array
 *
 * @param object $model
 * @param array $settings
 * @return array
 */
    protected function _normalizeFields($model, $settings = array()) {
		if (empty($settings)) {
			$settings = $this->settings[$model->alias];
		}
        $result = array();
        foreach ($settings as $flag => $date) {
            if (is_numeric($flag)) {
                $flag = $date;
                $date = false;
            }
            $result[$flag] = $date;
        }
        return $result;
    }

/**
 * Modifies conditions of hasOne and hasMany associations
 *
 * If multiple delete flags are configured for model, then $active=true doesn't
 * do anything - you have to alter conditions in association definition
 *
 * @param object $model
 * @param mixed $active
 */
    protected function _softDeleteAssociations($model, $active) {
        if (empty($model->belongsTo)) {
			return;
		}
		$fields = array_keys($this->_normalizeFields($model));
		$parentModels = array_keys($model->belongsTo);

		foreach ($parentModels as $parentModel) {
			foreach (array('hasOne', 'hasMany') as $assocType) {
				if (empty($model->{$parentModel}->{$assocType})) {
					continue;
				}

				foreach ($model->{$parentModel}->{$assocType} as $assoc => $assocConfig) {
					$modelName = empty($assocConfig['className']) ? $assoc : @$assocConfig['className'];
					if ($model->alias != $modelName) {
						continue;
					}

					$conditions =& $model->{$parentModel}->{$assocType}[$assoc]['conditions'];
					if (!is_array($conditions)) {
						$model->{$parentModel}->{$assocType}[$assoc]['conditions'] = array();
					}

					$multiFields = 1 < count($fields);
					foreach ($fields as $field) {
						if ($active) {
							if (!isset($conditions[$field]) && !isset($conditions[$assoc . '.' . $field])) {
								if (is_string($active)) {
									if ($field == $active) {
										$conditions[$assoc . '.' . $field] = false;
									}
									elseif (isset($conditions[$assoc . '.' . $field])) {
										unset($conditions[$assoc . '.' . $field]);
									}
								}
								elseif (!$multiFields) {
									$conditions[$assoc . '.' . $field] = false;
								}
							}
						} elseif (isset($conditions[$assoc . '.' . $field])) {
							unset($conditions[$assoc . '.' . $field]);
						}
					}
				}
			}
		}
    }
}
