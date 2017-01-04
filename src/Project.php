<?php 

namespace DaveJToews\FreshbooksToSlack;

class Project {

	public $name;

	public function __construct($api_array) {
		$this->name = $api_array['name'];
	}
}