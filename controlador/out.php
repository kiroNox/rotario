<?php 
	if(session_status() === PHP_SESSION_ACTIVE){
		session_unset();
		session_destroy();
	}
	if(isset($_GET["APP-REQUEST"])){
		die("out_session");
	}
	else{
		header("location: ".BASE_URL);
	}
 ?>