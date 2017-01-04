<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__ . '/vendor/autoload.php';

	use Noodlehaus\Config;

	function isClientProject($fb, $project_id) {
		$conf = new Config('config.json');

		$fb->setMethod('project.get');
		$fb->post(array(
		    'project_id' => $project_id
		));

		$fb->request();

		if($fb->success()) {
			$project = $fb->getResponse()['project'];
			return true;
			//if ($project['client_id'] == $conf->get('freshbooks.client_id')) {
			//	return true;
			//} else {
			//	return false;
			//}
		} else {
	 		echo $fb->getError();
		    var_dump($fb->getResponse());
		    return false;
		}	
	}

	function handleFreshbooksEvent($name, $object_id) {
		$conf = new Config('config.json');
		$fb = new Freshbooks\FreshBooksApi($conf->get('freshbooks.domain'), $conf->get('freshbooks.token'));

		$name_split = explode('.', $name);
		$service = $name_split[0];
		$action = $name_split[1];

		switch ($service) {
			case 'time_entry':
				handleTimeEntryEvent($fb, $action, $object_id);
				break;
			default: 
				return;
		}

	}

	function handleTimeEntryEvent($fb, $action, $object_id) {

	    // Method names are the same as found on the freshbooks API
		$fb->setMethod('time_entry.get');

		// For complete list of arguments see FreshBooks docs at http://developers.freshbooks.com
		$fb->post(array(
		    'time_entry_id' => $object_id
		));

		$fb->request();

		if($fb->success()) {
			$time_entry = $fb->getResponse()['time_entry'];
			if (isClientProject($fb, $time_entry['project_id'])) {
				echo "yep!!";
			} else {
				echo "nope211";
			}
		} else {
	 		echo $fb->getError();
		    var_dump($fb->getResponse());
		}
	}

	if(file_exists('config.json')) {
		echo "Config.json found\n";

		if (isset($_POST['name']) && isset($_POST['object_id'])) {
			echo "Freshbooks event submitted\n";
			handleFreshbooksEvent($_POST['name'], $_POST['object_id']);
		}

	} else {
		echo "Config.json not found";
	}
	
    //file_put_contents("post.txt", print_r($_POST, true));
 ?>
