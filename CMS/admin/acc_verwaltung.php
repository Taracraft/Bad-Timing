<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../cms/index.html');
	exit;
}
include("../../cms/style/template/header.php");
?>
<div class="content">
<h2>Admin-Bereich</h2>
<p><a href="../../cms/admin/acc_add.php"><i class="fas fa-user-circle"></i>Benutzer hinzuf&uuml;gen</a></p>
<p><a href="../../cms/admin/acc_list.php"><i class="fas fa-user-circle"></i>Benutzer Editieren/L&ouml;schen</a></p>
</div>
<?
include("../../cms/style/template/footer.php");
?>