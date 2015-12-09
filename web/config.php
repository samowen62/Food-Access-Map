<?php 
	$prod = FALSE;
	//$prod = TRUE;

	if($prod){
		$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	}else{
		$url = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => 'Farkin8or',
			'path' => '/food_access'
		);

	}

?>