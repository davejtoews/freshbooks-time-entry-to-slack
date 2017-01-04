<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__ . '/vendor/autoload.php';

	function printPageList($count) { ?>
	
		<ul>
			<?php for ($i=1; $i<=$count; $i++) : ?>
				<li>
					<a href="?page=<?= $i ?>">Page <?= $i ?></a>
				</li>
			<?php endfor; ?>
		</ul>

	<?php }

	function printClientList($clients) {?>

		<ul>
			<?php foreach ($clients as $client) : ?>
				<li><?= $client['organization'] ?>: <?= $client['client_id'] ?></li>
			<?php endforeach; ?>
		</ul>

	<?php }

	use Noodlehaus\Config;

	if(file_exists('config.json')) {
		$conf = new Config('config.json');

		if($conf->get('freshbooks.list_clients')) {
			$fb = new Freshbooks\FreshBooksApi($conf->get('freshbooks.domain'), $conf->get('freshbooks.token')); 
		    
		    	// Method names are the same as found on the freshbooks API
			$fb->setMethod('client.list');

			$page = 1;

			if (isset($_GET['page'])) {
				$page = $_GET['page'];
			}

			// For complete list of arguments see FreshBooks docs at http://developers.freshbooks.com
			$fb->post(array(
			    'per_page' => '100',
			    'page' => $page
			));

			$fb->request();

			
			if($fb->success()) {
			    $response = $fb->getResponse();
			    printPageList($response['clients']['@attributes']['pages']);
			    printClientList($response['clients']['client']);
			} else {
		 		echo $fb->getError();
			    var_dump($fb->getResponse());
			}
	    				
		} else {
			echo "Set list_clients to true to list clients";
		}



	} else {
		echo "Config.json not found";
	}
	
 ?>
