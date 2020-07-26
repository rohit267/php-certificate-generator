
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$login="";
$error=false;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Certificate Genrator</title>

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
        <div class="col-xs-12"><h2>Workshop Certificate Genrator</h2></div>
      </div>
  		<div class="row" style="margin-top: 60px;">
  			<?php
  			if(isset($_COOKIE['_RECWRSP']) && $login==false){
  				?>


  			<div class="col-md-4"></div>
  			<div class="col-md-4">
  				<div class="panel">
  					<form method="POST" action="" enctype="multipart/form-data">
	  					<div class="panel-head" style="border-bottom: 1px solid white">
	  						<h3 style="padding-left: 20px">Create New Workshop</h3>
	  					</div>
	  					<div class="panel-body">
	  						<div>
	  							<label>Event Name: </label>
	  							<input class="form-control" type="text" name="eventName"  required placeholder="Python Workshop">
	  						</div>
                <br>
	  						<div>
	  							<label>Choose CSV to genrate:</label>
	  							<input type="file" accept=".csv" name="mCsv" required />
	  						</div>
	  						<br>
	  						<span><?php echo $error?></span>
	  						<br>
	  						<div class="text-center">
	  							<input style="color: black" class="btn" type="submit" name="submit" value="Submit">
	  						</div>
	  					</div>
  					</form>
  				</div>
  				</div>
  			</div>
  			<div class="col-md-4"></div>
  			<?php
  			}
  			else{
  				header('location: index.php');
  			}
  			?>
  		</div>
  	</div>
  </body>
</html>

<?php
$error="";
if($_POST){
  require '../private/php/portaldbiconnect.php';
  extract($_POST);
  $eventName=ucwords(addslashes($eventName));
  if(isset($_FILES['mCsv'])){
    $errors= array();
    $file_name = $_FILES['mCsv']['name'];
    $file_size =$_FILES['mCsv']['size'];
    $file_tmp =$_FILES['mCsv']['tmp_name'];
    $file_type=$_FILES['mCsv']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['mCsv']['name'])));
    
    $extensions= array("csv");
    
    if(in_array($file_ext,$extensions)=== false){
       $errors[]="extension not allowed, please choose a CSV";
    }
    $file_name=time()."_".$file_name;
    $file_location="csv/".$file_name;
    if(empty($errors)==true){
       if(move_uploaded_file($file_tmp,$file_location)){
          $res=mysqli_query($conn,"insert into workshopEvent(eventName,csvLocation,genrated) values('".$eventName."','".$file_location."','NO')");
          if($res){
            header('location: index.php');
          }
          else{
            echo mysqli_error($conn);
          }
       }
       echo "Success";
    }else{
       print_r($errors);
    }
  }
  else{
    echo "Error, file not uploaded";
  }
}
?>