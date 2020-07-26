
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$error="";
$login=false;
if($_POST){
	extract($_POST);
	//var_dump($_POST);
	if($username=="admin" && $password=="admin"){
		setcookie('_RECWRSP',base64_encode('CSEDEPT'),time()+(30*86400));
		$login=true;
	}
	else{
		$error="Wrong username or password.";
	}
}
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
  			if(!isset($_COOKIE['_RECWRSP']) && $login==false){
  				?>


  			<div class="col-md-4"></div>
  			<div class="col-md-4">
  				<div class="panel">
  					<form method="POST" action="">
	  					<div class="panel-head" style="border-bottom: 1px solid white">
	  						<h3 style="padding-left: 20px">Login</h3>
	  					</div>
	  					<div class="panel-body">
	  						<div>
	  							<label>Username:</label>
	  							<input class="form-control" type="text" name="username" maxlength="10" required>
	  						</div>
	  						<div>
	  							<label>Password:</label>
	  							<input class="form-control" type="password" minlength="8" name="password" maxlength="10" required="">
	  						</div>
	  						<br>
	  						<span><?php echo $error?></span>
	  						<br>
	  						<div class="text-center">
	  							<input style="color: black" class="btn" type="submit" name="submit" value="Login">
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
  				?>
  				<div class="col-md-2">
            <a class="btn btn-primary" href="createNew.php">Create New</a><br>
          </div>
  				<div class="col-md-8">
  					<table border="2" class="table table-stripped" style="margin-top: 20px">
  						<tr><th>Workshop ID</th><th>Workshop Name</th><th>Cert. Genrated</th><th>Action</th><th>Share Link</th></tr>
  						<?php 
  						require_once '../private/php/portaldbiconnect.php';
  						$res=mysqli_query($conn,"select * from workshopEvent");
              if(mysqli_num_rows($res)>0){
                while ($row=mysqli_fetch_assoc($res)) {
                ?>
                <tr><td><?php echo $row['id']?></td><td><?php echo $row['eventName']?></td><td><?php echo $row['genrated']?></td><td><?php
              if($row['genrated']=="YES"){
                echo "Genrated";
              } else{
                echo "<a href='genrate.php?id=".base64_encode($row['id'])."'>Genrate</a>";
              }
              ?></td><td>
              <?php
              if($row['genrated']=="YES"){
                echo "<a href='download.php?id=".base64_encode($row['id'])."'>Share</a>";
                
              } else{
                echo "Genrate First";
              }

              ?></td></tr>
                <?php
                }
              }
              else{
                echo "<tr class='text-center'><td colspan='5'>No workshops created.</td>";
              }
  						
  						?>
  					</table>
  				</div>
  				<div class="col-md-2"></div>
  				<?php
  			}
  			?>
  		</div>
  	</div>
  </body>
</html>