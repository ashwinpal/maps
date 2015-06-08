<?php

	if (isset($_POST['submit'])) {
		# code...
		session_start();
		
		$_SESSION ['username'] = $_POST['uname'];
	 	$_SESSION ['side'] = $_POST['side'];
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Scotland Yard</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div>
	<div class="main">
		<form action="gamemap.php" method="post">		
			<h2>Enter Your Name</h2>
				<input type="text" name="uname" id="name" placeholder="Your name" />
				<h2>Select Your Side</h2>

		    <input type="radio" id="radio1" name="side" value="all" checked>
		       <label for="radio1">POLICE</label>
		    <input type="radio" id="radio2" name="side"value="false">
		       <label for="radio2">THEIF</label>

		    <input type="submit" name="submit" />
		</form>
	</div>
</div>
</body>
</html>