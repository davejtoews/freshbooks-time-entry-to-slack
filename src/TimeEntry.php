<?php 

namespace DaveJToews\FreshbooksToSlack;

class TimeEntry {

	public $project_id;
	public $project;
	public $date;
	public $hours;
	public $notes;

	public function __construct($api_array) {
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

}