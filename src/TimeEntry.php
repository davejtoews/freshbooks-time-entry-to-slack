<?php 

namespace DaveJToews\FreshbooksToSlack;

use Noodlehaus\Config;

class TimeEntry {

	public $project_id;
	public $project;
	public $date;
	public $hours;
	public $notes;

	private $conf;

	public function __construct($api_array) {
		$this->conf = new Config('config.json');
		$this->date = $api_array['date'];
		$this->hours = $api_array['hours'];
		$this->notes = $api_array['notes'];
		$this->project_id = $api_array['project_id'];
	}

	public function setProject($project) {
		$this->project = $project;
	}

	public function getSlackString() {
		$output = 	$this->date . "\n";
		$output .= 	"*" . $this->project->name . "* _" . $this->hours . " hours_\n";
		$output .= $this->notes;
		return $output;
	}

	public function shouldPostToSlack() {
		return $this->isClientProject();
	}

	private function isClientProject() {
		$conf = $this->conf;

		if ($this->project->client_id == $conf->get('freshbooks.client_id')) {
			return true;
		} else {
			return false;
		}
	}

}