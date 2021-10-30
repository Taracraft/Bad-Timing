<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../cms/index.html');
	exit;
}
include("../../cms/config/db.php");
include("../../cms/style/template/header.php");
?>
<div class="content">
<h2>Benutzer-Liste</h2>
<?
// Create connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT id, username, password, email FROM accounts";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    echo "<div class='contents'>";
    echo "<table><tr><th>ID</th><th>Username</th><th>Passwort</th><th>E-Mail-Adresse</th></tr></table></div>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='contents'>";
        echo "<th>" . $row['id']. "\t" . "</th>";
        echo "<th><a href=useredit.php?var=$row[id]' 
            onClick='window.open(\"useredit.php\",\"Fenster\",\"width=310,height=400,left=0,top=0\"); return false;
            '>$row[username] \t</a></th>";
        echo "<th><a href=pwedit.php?var=$row[id]'
            onClick='window.open(\"useredit.php\",\"Fenster\",\"width=310,height=400,left=0,top=0\"); return false;
            '>$row[password] \t</a></th>";
        echo "<th><a href=mailedit.php?var=$row[id]'
            onClick='window.open(\"mailedit.php\",\"Fenster\",\"width=310,height=400,left=0,top=0\"); return false;
            '>$row[email] \t</a></th>";
        echo "<br>";
        echo "</br>";
        echo "</table>";
        echo "</div>";
    }
}


?>
</div>
<?
include("../../cms/style/template/footer.php");

/*
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
    $link = mysqli_connect("localhost", "user", "password", "db");
    if (mysqli_connect_errno())
        die("Connect failed: " . mysqli_connect_error());
    $query = "Delete from `tabelle` where `id`=" . $_GET['id'];

    $result = mysqli_query($link, $query)
    or die ("MySQL-Error: " . mysqli_error($link));

    echo "Eintrag mit ID " . $_GET['id'] . " gelöscht";
}
else
    echo "Keine oder falsche Daten";
?>
<?php
$link = mysqli_connect("localhost", "user", "password", "db");
if (mysqli_connect_errno())
    die("Connect failed: " . mysqli_connect_error());
$query = "Select `id`, `name` from `tabelle`";

$result = mysqli_query($link, $query)
or die ("MySQL-Error: " . mysqli_error($link));

while ($row = mysqli_fetch_assoc($result))
    echo $row['name'] . "<a href='delete.php?id=" . $row['id'] . "'>Löschen</a><br>\n";

<p> <?php echo str_replace('?', '<br/> ●', $description); ?> </p>

*/
?>