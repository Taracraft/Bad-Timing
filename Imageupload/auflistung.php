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
                                <a href="home.php"><i class="fas fa-user-circle"></i>Upload der Images</a>
				<a href="auflistung.php"><i class="fas fa-user-circle"></i>Auflistung der Images</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
	</body>
</html>
<?
echo '<br><b>Auflistung!:</br></b>';
 echo '<br></br>';
 $ordner = "/var/www/vhosts/bad-timing.eu/httpdocs/Imageupload/images";
 $verzeichnis = opendir($ordner); 
        while ($file = readdir ($verzeichnis)) 
        {
        	
         if($file != "." && $file != "..") 
            {
             if(is_dir($ordner."/".$file)) 
                {
                 echo "/".$file."<br/>";
                    } 
                     else 
                    {
                     // kompletter Pfad
                     $compl = "https://bad-timing.eu/Imageupload/images"."/".$file;
                     echo '<br></br>';
                     echo "<img src=\"".$compl."\"></img><br/>";
                     echo "<center><h1>images/".$file."</h1></center>";
                     }
            }
        }
    echo "</select>";



?>