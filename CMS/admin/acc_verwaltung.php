<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
include("../../cms/style/template/header.php");
?>
<div class="content">
<h2>Admin-Bereich</h2>
<p>Benutzer hinzufügen</p>
<p>Benutzer Löschen</p>
<p>Benutzer Editieren</p>
</div>
<?
include("../../cms/style/template/footer.php");
?>