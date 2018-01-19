<?php
// EDIT EXISTING 
// you coming with get request and get auto_id 
	require_once "pdo.php";
	session_start(); //start the session

	//if the user is not logged in redirect back to index.pnp
	//with an error
	if ( ! isset($_SESSION['user_id']) ) {
    	die('Not logged in');
    	return;
	}


	//if the user requested cancel go back to index.php
	if( isset($_POST['cancel']) ) {
	  // Redirect the browser to index.php
	  header('Location: index.php');
	  return;
	}


	//STEP 3
	//post request
	//validating auto data
	if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) 
     && isset($_POST['headline']) && isset($_POST['summary']) ) {
     	if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
	      		$_SESSION['error'] = "All fields are required";
	      		header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
				return;
   		}else {
   			//using test_input function to prepare the data
		    $emailEdit = test_input($_POST["email"]);
		    //validating for email
		    if (!filter_var($emailEdit, FILTER_VALIDATE_EMAIL)) {
		      $_SESSION['error'] = "Email must have an at-sign (@)";
		      header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
		      return; 
     		}else {
	     		//data validation
				$sql = "UPDATE Profile SET first_name = :fn, last_name = :ln, 
					email = :ema, headline = :he, summary = :su 
					WHERE profile_id = :profile_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fn' => $_POST['first_name'],
					':ln' => $_POST['last_name'],
					':ema' => $_POST['email'],
					':he' => $_POST['headline'],
					':su' => $_POST['summary'],
					':profile_id' => $_POST['profile_id']));
				//displat the message
				$_SESSION['success'] = 'Record edited';
				header('Location: index.php'); //redirect to the index.php
				return;
	     	}
   		}
	}

	//check the input data
	function test_input($data) {
	  //The trim() function removes whitespace and other predefined characters from both sides of a string.
	  $data = trim($data);
	  //The stripslashes() function removes backslashes
	  $data = stripslashes($data);
	  //The htmlspecialchars() function converts some predefined characters to HTML entities.
	  $data = htmlspecialchars($data);
	  return $data;
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
			<h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?> </h1>
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
				<p>First Name:
					<input type="text" name="first_name" value="<?= $fn ?>"></p>
				<p>Last Name:
					<input type="text" name="last_name" value="<?= $ln ?>"></p>
				<p>Email:
					<input type="text" name="email" value="<?= $ema ?>"></p>
				<p>Headline:
					<input type="text" name="headline" value="<?= $he ?>"></p>
				<p>Summary:<br>
					<input type="text" name="summary" rows="8" cols="80" value="<?= $su ?>"></p>
				<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
				<p>
					<input type="submit" value="Save"/>
					<a href="index.php">Cancel</a>
				</p>
			</form>
		</div>
	</body>
</html>
