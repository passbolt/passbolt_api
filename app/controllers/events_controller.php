<?php
App::import('Model', 'Password');

class EventsController extends AppController {
	var $name = 'Events';

	function retrieveForCategory($offset=0){
		//$aco_id = 1;
		//$aco_ref_id = 5;
		$nb_results = 5;
		
		$aco_id = $this->params["form"]['aco_id'];
		$aco_ref_id = $this->params["form"]['aco_ref_id'];
		
		$conditions = null;
		if($aco_id == ACO_CATEGORY){
			$conditions = array("Event.category_rel_id"=>$aco_ref_id);
		}
		else{
			$conditions = array("Event.aco_id"=>$aco_id, "Event.aco_ref_id"=>$aco_ref_id);
		}
		
		$fields = array("Event.id", "Event.action_id", "Event.aco_id", "Event.aco_ref_id", "Event.details", "Event.date", "Category.name", "Password.title", "User.name");
		$events = $this->Event->find('all', array("fields"=>$fields, "conditions"=>$conditions, "order"=>"Event.date DESC", "limit"=>$nb_results, "offset"=>$offset));
		$eventsCount = $this->Event->find('count', array("conditions"=>$conditions));
		
		$this->set('count', $eventsCount);
		$this->set('offset', $offset);
		$this->set('nb_results', $nb_results);
		$this->set('events', $events);
	}
	
	function retrieveForUser($offset=0){
		$user_id = $this->params["form"]['user_id'];
		//print_r($this->params);
		//$user_id = 17;
		$fields = array("Event.id", "Event.action_id", "Event.aco_id", "Event.aco_ref_id", "Event.details", "Event.date", "Category.name", "Password.title", "User.name");
		
		$conditions = array("Event.created_by"=>$user_id);
		
		$events = $this->Event->find('all', array("fields"=>$fields, "conditions"=>$conditions, "order"=>"Event.date DESC", "limit"=>5, "offset"=>$offset));
		$this->set('events', $events);
		$this->render('retrieve_for_category');
	}
	
	/**
	 * 
	 * Get the list of events according to specific filters criterias
	 * @param $offset : where to start from (entry offset)
	 */
	function retrieve($offset=0){
		$user = $this->Auth->user();
		$nb_results = 20;
			
		if(isset($this->data)){
			$conditions = array();
			if(!empty($this->data['User']['id'])){
				$conditions["Event.created_by"] = $this->data['User']['id'];
				
			}
			if(!empty($this->data['Event']['action_id'])){
				$conditions["Event.action_id"] = $this->data['Event']['action_id'];
			}
			if(!empty($this->data['Event']['datefrom'])){
				$datefrom = date('Y-m-d', strtotime($this->data['Event']['datefrom']));
				$dateto = (!empty($this->data['Event']['dateto']) ? date('Y-m-d', strtotime($this->data['Event']['dateto'])) : date('Y-m-d', strtotime("+1 day", strtotime($this->data['Event']['datefrom']))));
				$conditions["Event.date BETWEEN ? AND ?"] = array($datefrom, $dateto);
			}
			$events = $this->Event->find('all', array("conditions"=>$conditions, "order"=>"Event.date DESC", "limit"=>$nb_results, "offset"=>$offset));
			$eventsCount = $this->Event->find('count', array("conditions"=>$conditions));
		}
		else{		
			if(isset($this->params["form"]['user_id'])){
				$user_id = $this->params["form"]['user_id'];
			}
			$events = $this->Event->find('all', array("order"=>"Event.date DESC", "limit"=>$nb_results, "offset"=>$offset));
			$eventsCount = $this->Event->find('count');
		}
		$this->section = 'activity';
	
		$this->set('events', $events);
		$this->set('count', $eventsCount);
		$this->set('offset', $offset);
		$this->set('nb_results', $nb_results);
		
		if($this->RequestHandler->isAjax()){ $this->render('retrieveAjax'); }
	}
	
	/**
	 * Registers an event (through ajax)
	 * @param $action_id : the type of event
	 */
	function register($action_id){
		// for the moment, can only register get_password event
		if($action_id == WATCHER_GET_PASSWORD){
			$id = $this->params["form"]['id'];
			$passwordModel = new Password();
			$category = $passwordModel->findById($id);
			$category_id = $category["Password"]["category_id"];
			$this->Watcher->logAction(WATCHER_GET_PASSWORD, ACO_PASSWORD, $id, null, $category_id); // logs the create new category action
			$res = array("status"=>1);
		}
		$this->set('json', $res);
		$this->render('/javascript/json');
	}
}