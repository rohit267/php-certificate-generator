
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Certificate Download</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
    .panel{
    	background: #0047b3;
    	color: white;
    }
    </style>
  </head>
  <body>
  	<div class="container">
      <div class="row text-center" style="background: #514FF5;color: white">
        <div class="col-xs-12"><h2>Workshop Certificate Download</h2></div>
      </div>
  		<div class="row" style="margin-top: 60px;">
  			<div class="col-md-4"></div>
  			<div class="col-md-4">
  				<div class="panel">
  					<form method="POST" action="">
	  					<div class="panel-head" style="border-bottom: 1px solid white">
	  						<h3 style="padding-left: 20px">Download</h3>
	  					</div>
	  					<div class="panel-body">
	  						<div>
	  							<label>Entered Registered Email: </label>
	  							<input class="form-control" type="email" name="email"  required placeholder="youremail@example.com">
	  							<input type="hidden" name="id" value="<?php echo base64_decode($_GET['id']) ?>">
	  						</div>
	  						<br>
	  						<div class="text-center">
	  							<input style="color: black" class="btn" type="submit" name="submit" value="Download">
	  						</div>
	  					</div>
  					</form>
  				</div>
  				</div>
  			</div>
  			<div class="col-md-4"></div>
  		</div>
  	</div>
  </body>
</html>

<?php
if($_POST){
	extract($_POST);
	require '../private/php/portaldbiconnect.php';
	$res=mysqli_query($conn,"select * from workshopEvent where id=".$id);
	$row=mysqli_fetch_assoc($res);
	$name=$row['eventName'];
	$file="output/".$email."_".$id."_".$name.".pdf";
	if(file_exists($file)){
		header('location: '.$file);
	}
	else{
		echo "<h2>Certificate Not Found</h2>";
	}
}
?>