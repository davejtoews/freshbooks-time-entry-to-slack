<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__ . '/vendor/autoload.php';

	use Noodlehaus\Config;

	if(file_exists('config.json')) {
		$conf = new Config('config.json');

		if($conf->get('freshbooks.list_clients')) {
			echo '<pre>';
			$fb = new Freshbooks\FreshBooksApi($conf->get('freshbooks.domain'), $conf->get('freshbooks.token')); 
		    
		    	// Method names are the same as found on the freshbooks API
			$fb->setMethod('client.list');

			// For complete list of arguments see FreshBooks docs at http://developers.freshbooks.com
			$fb->post(array(
			    'per_page' => '100'
			));

			$fb->request();

			if($fb->success()) {
			    echo 'successful! the full response is in an array below';
			    var_dump($fb->getResponse());
			} else {
		 		echo $fb->getError();
			    var_dump($fb->getResponse());
			}
	    	echo '</pre>';			
		} else {
			echo "Set list_clients to true to list clients";
		}



	} else {
		echo "Config.json not found";
	}
	

	//print_r($_POST);
    //file_put_contents("post.txt", print_r($_POST, true));
 ?>
