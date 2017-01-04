<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__ . '/vendor/autoload.php';

	use Noodlehaus\Config;
	use DaveJToews\FreshbooksToSlack;

	function postToSlack($text) {
		$settings = [
		    'username' => 'timesheet-bot',
			'icon' => ':robot_face:'	    
		];
		$client = new Maknz\Slack\Client('https://hooks.slack.com/services/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', $settings);

		$client->send("hallo\nhallo again");
	}

	if(file_exists('config.json')) {
		echo "Config.json found\n";

		if (isset($_POST['name']) && isset($_POST['object_id'])) {
			echo "Freshbooks event submitted\n";
			$freshbooks_event = new FreshbooksToSlack\FreshbooksEvent();
			$freshbooks_event->handle($_POST['name'], $_POST['object_id']);
			print_r($freshbooks_event);
		}

	} else {
		echo "Config.json not found";
	}

	
    //file_put_contents("post.txt", print_r($_POST, true));
 ?>
