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

		<div class="content">
			<h2>Image-Upload</h2>
			<form action="../../cms/funktionen/upload_funktion.php" method="post" enctype="multipart/form-data">
			<input type="file" name="datei"><br>
			<input type="submit" value="Hochladen">
			</form>
		</div>

<?
include("style/template/footer.php");
?>

