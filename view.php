<?php
// EDIT EXISTING 
// you coming with get request and get auto_id 
	require_once "pdo.php";
	session_start(); //start the session


	//if the user requested cancel go back to index.php
	if( isset($_POST['cancel']) ) {
	  // Redirect the browser to index.php
	  header('Location: index.php');
	  return;
	}
	
	//STEP 1 
	//checking if row exists
	$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id'] ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for profile_id';
		header('Location: index.php');
		return;
	}

    //view 
	//STEP 2
	//checking the user input
	$fn = htmlentities($row['first_name']);
	$ln = htmlentities($row['last_name']);
	$ema = htmlentities($row['email']);
	$he = htmlentities($row['headline']);
	$su = htmlentities($row['summary']);
	$profile_id = $row['profile_id'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Iuliia Zemliana - Users and Profiles</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<h1>Profile information</h1>
		<?php
			if ( isset($_SESSION['error']) ) {
				echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
				unset($_SESSION["error"]);
			}
			if ( isset($_SESSION['success']) ) {
				echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
				unset($_SESSION["success"]);
			}
		?>
		<form method="post">
			<p>First Name: <?php echo($fn); ?> </p>
			<p>Last Name: <?php echo($ln); ?> </p>
			<p>Email: <?php echo($ema); ?> </p>
			<p>Headline: <?php echo($he); ?> </p>
			<p>Summary: <?php echo($su); ?> </p>
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
			<p>
				<a href="index.php">Done</a>
			</p>
		</form>
	</div>
</body>
</html>

