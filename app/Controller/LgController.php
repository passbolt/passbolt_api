<?php

App::uses('Controller', 'Controller');
App::uses('I18n', 'I18n');


/**
 * Language Controller
 *
 * Manage all about language.
 *
 * @package       app.Controller
 */
class LgController extends AppController {

    /**
     * Get the jsDictionnary
     * @todo cache the json dictionnary
     */
    function jsDictionnary()
    {
        $returnValue = array();
        $this->layout = '';
        $this->autoRender=false;
        $locale = Configure::read('Config.language');
        $dicoName = 'jsDictionnary';
        
        $translatedFile = env('DOCUMENT_ROOT').'/app/Locale/'.$locale.'/LC_MESSAGES/'.$dicoName.'.po';
        if(file_exists($translatedFile)){
            //$translatedFile = env('DOCUMENT_ROOT').'/app/Locale/'.$dicoName.'.pot';
            $returnValue = I18n::loadPo($translatedFile);
        }
        
        echo json_encode($returnValue);
    }
    
    /**
     * Change the application language 
     */
    function change()
    {
        $locale = 'fr-FR';
        Configure::write('Config.language', $locale);
        $this->Session->write('Config.language', $locale);
    }
    
}
