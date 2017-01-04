<?php 

namespace DaveJToews\FreshbooksToSlack;

use Noodlehaus\Config;

class SlackClient {

	private $client;
	private $conf;

	public function __construct() {
		$this->conf = new Config('config.json');

		$settings = [
		    'username' => 'timesheet-bot',
			'icon' => ':robot_face:'	    
		];
		$this->client = new \Maknz\Slack\Client($this->conf->get('slack.webhook'), $settings);

	}

	public function send($text) {
		$this->client->send($text);
	} 
}