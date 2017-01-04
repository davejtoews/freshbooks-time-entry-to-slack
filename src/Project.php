<?php 

namespace DaveJToews\FreshbooksToSlack;

class Project {

	public $name;
	public $client_id;

	public function __construct($api_array) {
		$this->name = $api_array['name'];
		$this->client_id = $api_array['client_id'];
	}
}