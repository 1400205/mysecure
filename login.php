
<?php
session_start();
?>
<?php
include("connection.php"); //Establishing connection with our database

$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{
	if(empty($_POST["username"]) || empty($_POST["password"]))
	{
		$error = "Both fields are required.";
	}else
	{
		// Define $username and $password
		$username=$_POST['username'];
		$password=$_POST['password'];

		//clean input photo user name
		$username = stripslashes( $username );
		$username=mysqli_real_escape_string($db,$username);
		$username = htmlspecialchars($username);
		$password=md5($password);



		//implement prepared statement to take of sql injection and other vulnerabilities

		//declare instance of connection
		$sqlcon=new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if (!($sqlcon->connect_errno)){
			echo"connection Failed";
		}

		//prepare statement
		if($stmt=$sqlcon->prepare("SELECT userID FROM users WHERE username=? and password=?")){
			//bind parameter
			$stmt->bind_param('ss',$username,$password);
			$stmt->execute();
			//get result
			$result = $stmt->get_result();
		}


		if( ($row=$result->fetch_row()))
		{
			$_SESSION['username'] = $username; // Initializing Session
			$_SESSION["userid"] = $userid;//user id assigned to session global variable
			header("location: photos.php"); // Redirecting To Other Page
		}else
		{
			$error = "Incorrect username or password.";
		}

	}
}

?>
