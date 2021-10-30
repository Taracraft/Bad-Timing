<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../cms/index.html');
	exit;
}
?>
<?
include("style/template/header.php");
include("../../cms/style/template/nav.php");
?>
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


include("style/template/footer.php");
?>