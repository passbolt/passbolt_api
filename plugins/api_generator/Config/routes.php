<?php
/**
 * Routes for the API Generator plugin.
 *
 * Gives nice short routes for controllers in ApiGenerator
 */
Router::connect('/classes', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'index'));
Router::connect('/class/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'view_class'));
Router::connect('/source/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'source'));
Router::connect('/files/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'files'));
Router::connect('/packages', array('plugin' => 'api_generator', 'controller' => 'api_packages', 'action' => 'index'));
Router::connect('/package/*', array('plugin' => 'api_generator', 'controller' => 'api_packages', 'action' => 'view'));
Router::connect('/file/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'view_file'));
Router::connect('/view_source/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'view_source'));
Router::connect('/search/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'search'));

/**
 * Auto-detect json, so it doesn't mess up passedArgs
 */
Router::parseExtensions('json');
