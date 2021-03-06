<?php 

namespace DaveJToews\FreshbooksToSlack;

use Noodlehaus\Config;

class FreshbooksEvent {

	private $fb;
	private $conf;

	public $action;
	public $service_type;
	public $service;


	public function __construct($name, $object_id) {
		$this->conf = new Config('config.json');
		$this->fb = new \Freshbooks\FreshBooksApi($this->conf->get('freshbooks.domain'), $this->conf->get('freshbooks.token'));

		$name_split = explode('.', $name);
		$service_string = $name_split[0];
		$this->action = $name_split[1];

		switch ($service_string) {
			case 'time_entry':
				$this->service_type = 'Time Entry';
				$this->service = $this->getTimeEntry($object_id);
				break;
			default: 
				return;
		}

	}

	public function getSlackString() {
		$output = $this->action . " " . $this->service_type . " ";
		$output .= $this->service->getSlackString();
		return $output; 
	}

	public function shouldPostToSlack() {
		return $this->service->shouldPostToSlack();
	}

	private function getTimeEntry($object_id) {
		$fb = $this->fb;

	    // Method names are the same as found on the freshbooks API
		$fb->setMethod('time_entry.get');

		// For complete list of arguments see FreshBooks docs at http://developers.freshbooks.com
		$fb->post(array(
		    'time_entry_id' => $object_id
		));

		$fb->request();

		if($fb->success()) {
			$time_entry_array = $fb->getResponse()['time_entry'];
			$time_entry = new TimeEntry($time_entry_array);
			$project = $this->getProject($time_entry->project_id);
			$time_entry->setProject($project);
			return $time_entry;

		} else {
	 		echo $fb->getError();
		    var_dump($fb->getResponse());
		    return false;
		}
	}

	private function getProject($project_id) {
		$fb = $this->fb;
		$conf = $this->conf;

		$fb->setMethod('project.get');
		$fb->post(array(
		    'project_id' => $project_id
		));

		$fb->request();

		if($fb->success()) {
			$project_array = $fb->getResponse()['project'];
			return new Project($project_array);
		} else {
	 		echo $fb->getError();
		    var_dump($fb->getResponse());
		}	
	}
}