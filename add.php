<?php
	//make the database connection and leave it in the variable $pdo
	require_once "pdo.php";

	session_start();
	
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

	//validating auto data
	if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) 
     && isset($_POST['headline']) && isset($_POST['summary']) ) {

     	if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
      		$_SESSION['error'] = "All fields are required";
      		header("Location: add.php");
			return;
   		} 
   		else {
   			//using test_input function to prepare the data
		    $emailAdd = test_input($_POST["email"]);
		    //validating for email
		    if (!filter_var($emailAdd, FILTER_VALIDATE_EMAIL)) {
		      $_SESSION['error'] = "Email must have an at-sign (@)";
		      header("Location: add.php");
		      return; 
     		}else {
	     		$stmt = $pdo->prepare('INSERT INTO Profile
	        	(user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :ema, :he, :su)');
		    	$stmt->execute(array(
		    	':uid' => $_SESSION['user_id'],
		        ':fn' => $_POST['first_name'],
		        ':ln' => $_POST['last_name'],
		        ':ema' => $_POST['email'],
	        	':he' => $_POST['headline'],
	        	':su' => $_POST['summary']));
	        	$_SESSION['success'] = "Profile added";
				header("Location: index.php");
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
?>

<!DOCTYPE html>
<html>
  <head>
	  <title>Iuliia Zemliana - Autos Database CRUD</title>
	  <!-- Latest compiled and minified CSS -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>

<body>
	<div class="container">
		<?php
		echo('<h1>Adding Profile for '.htmlentities( $_SESSION["name"])."</h1>\n");

		if ( isset($_SESSION['error']) ) {
			echo('<p style="color:red">'.htmlentities( $_SESSION["error"] )."</p>\n");
			unset($_SESSION["error"]);
		}
		if ( isset($_SESSION['success']) ) {
			echo('<p style="color:green">'.htmlentities( $_SESSION["success"] )."</p>\n");
			unset($_SESSION["success"]);
		}

		//check if we are logged in!
		if( ! isset($_SESSION["name"]) ) { 
			echo('<p>You are not logged in </p>');
		}
		?>
		<form method="post">
			<p>First Name:
				<input type="text" name="first_name" size="60"/></p>
			<p>Last Name:
				<input type="text" name="last_name" size="60"/></p>
			<p>Email:
				<input type="text" name="email"/></p>
			<p>Headline:
				<input type="text" name="headline"/></p>
			<p>Summary:<br>
				<textarea name="summary" rows="8" cols="80"></textarea></p>
			<input type="submit" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
	</body>
</html>



