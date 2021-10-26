<?
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../cms/index.html');
	exit;
}
include("../../cms/style/template/header.php");
include("../../cms/config/db.php");
	
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME); 
$option = [ "cost" => 15 ];
$pwhash = password_hash($password, PASSWORD_BCRYPT, $option);

if(isset($_GET['register'])) {
    $error = false;
    $username =$_POST['username'];
    $email = $_POST['email'];
    $passwort = $_POST['password'];
    $passwort2 = $_POST['password2'];
    
    if(!filter_var($username)== 0) {
        echo 'Bitte ein Username eingeben<br>';
        $error = true;
    }     

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
        $error = true;
    }     
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }
    
    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) { 
        $statement = $con->prepare("SELECT * FROM accounts WHERE email = :email");
        $result = $con->execute([":email" => $email]);
        $user = $con->fetch();
        
        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }    
    }
        
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {    
        $passwort_hash = password_hash($passwort, PASSWORD_BCRYPT, $option);
    	$stmt = $con->prepare("INSERT INTO accounts (username, email, passwort) VALUES (:username, :email, :passwort)");    
        $result = $con->execute(array('username' => $username, 'email' => $email, 'passwort' => $passwort_hash));
        
        if($result) {        
            echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    } 
}
if($showFormular) {
} //Ende von if($showFormular)
?>
<div class="login">
			<h1>Login</h1>
			<form action="?register=1" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				
				<input type="text" name="username" placeholder="Benutzername" id="username" erfoderlich>
				<label for="email">
					<i class="fas fa-user"></i>
				</label>
				
				<input type="text" name="email" placeholder="E-Mail Adresse" id="email" erfoderlich>

				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" erforderlich>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password2" placeholder="Password" id="password" erforderlich>

				<input type="submit" value="Login">
			</form>
</div>
<?



include("../../cms/style/template/footer.php");
?>
