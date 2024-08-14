<?php
session_start();

// Funktion zur Anzeige von Fehlermeldungen
include("../../cms/config/db.php");
include("../../cms/funktionen/funktionen.php");  // Angepasster Pfad zur funktionen.php
include("../../cms/style/template/header.php");
include("../../cms/style/template/nav.php");

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($con->connect_error) {
    display_error('Failed to connect to MySQL: ' . $con->connect_error);
}

// Benutzeraktionen behandeln
handle_user_actions($con);

// Benutzerliste anzeigen
$sql = "SELECT id, username, role, email, status FROM accounts";
$result = $con->query($sql);
?>

<div class="content">
    <h2>Benutzer-Liste</h2>
    <form method="post">
        <table class="user-table">
            <tr>
                <th><input type="checkbox" id="select_all"></th>
                <th>ID</th>
                <th>Username</th>
                <th>Rolle</th>
                <th>E-Mail-Adresse</th>
                <th>Passwort</th>
                <th>Deaktivieren</th>
                <th>Erneut Aktivieren erzwingen</th>
                <th>Löschen</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='user_ids[]' value='" . $row['id'] . "' class='user_checkbox'></td>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td><select name='new_role[" . $row['id'] . "]'>
                                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">Benutzer</option>
                                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Administrator</option>
                                <option value='deactivated' " . ($row['role'] == 'deactivated' ? 'selected' : '') . ">Deaktiviert</option>
                            </select></td>";
                    echo "<td><button type='button' onclick='openEmailModal(" . $row['id'] . ", \"" . $row['email'] . "\")'>E-Mail ändern</button></td>";
                    echo "<td><button type='button' onclick='openPasswordModal(" . $row['id'] . ")'>Passwort ändern</button></td>";
                    echo "<td><input type='checkbox' name='deactivate_user_ids[" . $row['id'] . "]' value='1' " . ($row['status'] == 'deactivated' ? 'checked' : '') . "></td>";
                    echo "<td><input type='checkbox' name='reactivate_user_ids[" . $row['id'] . "]' value='1'></td>";
                    echo "<td><input type='checkbox' name='delete_user_ids[" . $row['id'] . "]' value='1'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Keine Benutzer gefunden.</td></tr>";
            }
            ?>
        </table>
        <div class="actions">
            <input type="submit" value="Aktionen ausführen" class="action-button">
        </div>
    </form>
</div>

<!-- Modal HTML für E-Mail und Passwort Änderung -->

<script src="../../cms/funktionen/funktionen.js"></script>

<?php
$con->close();
include("../../cms/style/template/footer.php");
?>
