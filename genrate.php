<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_COOKIE['_RECWRSP'])){
	header('location: index.php');
}
else{
	require '../private/php/portaldbiconnect.php';
	if (isset($_GET['id'])) {
		$id=addslashes(base64_decode($_GET['id']));
		$res=mysqli_query($conn,'select * from workshopEvent where id='.$id);
		if($res){
			$resRow=mysqli_fetch_assoc($res);
			include 'certgen.php';
		}	
		else{
			echo mysqli_error($conn);
		}
	}	
	else{
		echo "<h2>404, Bad Request</h2>";
	}
}
?>