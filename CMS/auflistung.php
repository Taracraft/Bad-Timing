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
include("style/template/nav.php");
?>
<?
echo '<br><b>Auflistung!:</br></b>';
 echo '<br></br>';
 $ordner = "/var/www/vhosts/bad-timing.eu/httpdocs/cms/images";
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
                     $compl = "https://bad-timing.eu/cms/images/"."/".$file;
                     echo '<br></br>';
                     echo "<img src=\"".$compl."\"></img><br/>";
                     echo "<textarea id=\"textArea\">images/".$file."</textarea>";
                     echo "<button onclick=\"copyToClipBoard()\">Text Kopieren</button>";
                     echo "<button onclick=\"bilderdelete()\">Delete</button>";
             }
         }}
include("style/template/footer.php");
?>
