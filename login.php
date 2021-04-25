<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome operator</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/oplogin.css">
 <script src="js/oplogin.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<?php
$servername = "localhost";
$username = "**";
$password = "***";
$valid = 0;

try {
  $conn = new PDO("mysql:host=$servername;dbname=wordpress", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->query("SELECT password FROM operators where mobileNumber=".$_POST['email']);
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach ($stmt as $row)
	{
		if (strcmp($row['password'], $_POST['password']) == 0) {
			$valid = $valid + 1;
		}
	}
	if($valid != 1) {
		echo '<div class="alert alert-danger" role="alert">Invalid Password. <a href="home.html">Try again </a></div>';
	}
  $conn = null;
} catch(PDOException $e) {
	echo '<div class="alert alert-danger" role="alert">Invalid Password. <a href="home.html">Try again </a></div>';
}
if($valid ==1) {

	try {
 	 	$conn = new PDO("mysql:host=$servername;dbname=wordpress", $username, $password);
  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  		$stmt = $conn->query("SELECT name,mobileNumber,pending_amount,last_paid FROM cable_users");
  		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
?>

<body>
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6"><h2>Manage <b>Users</b></h2></div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Payment Due</th>
                    <th>Month(Due)</th>
                </tr>
            </thead>
            <tbody>
            	<?php
            		$i=0;
  					foreach ($stmt as $row)
						{	
							$i=$i + 1;
                			echo '<tr data-status="active">
                    		<td>'.$i.'</td>
                    		<td>'.$row['name'].'</td>
                    		<td>'.$row['mobileNumber'].'</td>
                    		<td>'.$row['pending_amount'].'</td>
                    		<td>'.$row['last_paid'].'</td>
                			</tr>';
					}
					?>
            </tbody>
        </table>
    </div>     
</body>
</html>  

<?php
} catch(PDOException $e) {
			echo '<div class="alert alert-danger" role="alert">Invalid Password. <a href="home.html">Try again </a></div>';
		}
}

