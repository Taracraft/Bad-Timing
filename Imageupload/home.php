<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>MC-Image-Upload</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="style.css.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>MC-Image-Upload</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Image-Upload</h2>
			<p>Willkommen zurück, <?=$_SESSION['name']?>!</p>
			<form action="upload.php" method="post" enctype="multipart/form-data">
			<input type="file" name="datei"><br>
			<input type="submit" value="Hochladen">
			</form>
		</div>
	</body>
</html>