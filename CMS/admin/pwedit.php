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




    </div>
<?
include("../../cms/style/template/footer.php");
?>