<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__ . '/vendor/autoload.php';

	use Noodlehaus\Config;
	use DaveJToews\FreshbooksToSlack;

	if(file_exists('config.json')) {
		echo "Config.json found\n";

		if (isset($_POST['name']) && isset($_POST['object_id'])) {
			echo "Freshbooks event submitted\n";
			$freshbooks_event = new FreshbooksToSlack\FreshbooksEvent($_POST['name'], $_POST['object_id']);
			$slack_client = new FreshbooksToSlack\SlackClient();

			$slack_client->send($freshbooks_event->getSlackString());
			echo "Posted to Slack\n";
		}

		$conf = new Config('config.json');

		if ($conf->get('freshbooks.post_log')) {
			$output = date("Y.m.d h:i:sa") . "\n" . print_r($_POST, true);
			file_put_contents("post_log.txt", $output, FILE_APPEND);
		}

	} else {
		echo "Config.json not found";
	}

 ?>
