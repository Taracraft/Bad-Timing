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
include("../style/template/header.php");
?>
<?
include("../style/template/nav.php");
?>
<?php
$upload_folder = '/var/www/vhosts/bad-timing.eu/httpdocs/cms/images/'; //Das Upload-Verzeichnis
$upload_folder_www = 'https://bad-timing.eu/cms/images/'; //Das HTTP-Verzeichnis
//Überprüfung ob der WWW-Pfad Lesbar /Schreibbar sind.
if ($upload_folder===false)
    {
    echo "Der Ordner existiert garnicht";
    } else
    {
    if (is_writeable($upload_folder))
        {
        echo "ok, koennte in den Ordner schreiben";
        $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
        } else
        {
        echo "no way, dude";
        $wwwschreibbar = false;
        }
    } 

if ($wwwschreibbar ='true')
    {
    // berpr fung der Dateiendung
    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
    if(!in_array($extension, $allowed_extensions)) {
    die("Ung ltige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
    }
    
    // berpr fung der Dateigr  e
    $max_size = 500*1024; //500 KB
    if($_FILES['datei']['size'] > $max_size) {
    die("Bitte keine Dateien gr  er 500kb hochladen");
    }
    
    // berpr fung dass das Bild keine Fehler enth lt
    if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
    $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
    if(!in_array($detected_type, $allowed_types)) {
    die("Nur der Upload von Bilddateien ist gestattet");
    }
    }
    //Pfad zum Upload
    $new_path = $upload_folder.$filename.'.'.$extension;
    $new_path_www = $upload_folder_www.$filename.'.'.$extension;
    
    //Neuer Dateiname falls die Datei bereits existiert
    if(file_exists($new_path)) { //Falls Datei existiert, h nge eine Zahl an den Dateinamen
    $id = 1;
    do {
    $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
    $id++;
    } while(file_exists($new_path));
    }
    
    //Alles okay, verschiebe Datei an neuen Pfad
    move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
    $src = "/home/Lobby_1.17/plugins/Images/images/";  // source folder or file
    $dest = "/var/www/vhosts/bad-timing.eu/httpdocs/cms/images/";   // destination folder or file        

    shell_exec("cp $src $dest");

    echo "<H2>Copy files completed!</H2>"; //output when done
    echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path_www.'">'.$new_path_www.'</a>';
    $upload = true;
    if($upload == 'true'){
    echo '<br></br>';
    echo '<br><b>Auflistung!:</br></b>';
    echo '<br></br>';
    $ordner = "/var/www/vhosts/bad-timing.eu/httpdocs/cms/images/";
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
                        echo "<center><h1>images/".$file."</h1></center>";
                        echo "<button onclick=\"bilderdelete()\">Delete</button>";                }
            }}
    }else{
            echo" Verzeichnis nicht schreibbar oder Exestiert nicht";
        }
    }
 ?>
<?
include("../style/template/footer.php");
?>