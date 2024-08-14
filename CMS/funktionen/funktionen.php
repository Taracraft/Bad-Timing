<?php
function register_or_update_user($host, $user, $pass, $dbname, $is_update = false) {
    // Verbindung zur Datenbank herstellen
    $con = mysqli_connect($host, $user, $pass, $dbname);
    if (mysqli_connect_errno()) {
        return '<div class="error-message">Failed to connect to MySQL: ' . mysqli_connect_error() . '</div>';
    }

    // Prüfen ob die notwendigen Daten übermittelt wurden
    if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
        return '<div class="error-message">Bitte alles ausfüllen!</div>';
    }

    // Sicherstellen, dass die übermittelten Registrierungswerte nicht leer sind
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        return '<div class="error-message">Bitte alles ausfüllen!</div>';
    }

    // Validierung der E-Mail-Adresse
    $email_validation = validate_email($_POST['email']);
    if ($email_validation !== true) {
        return $email_validation;
    }

    // Überprüfen des Benutzernamens auf Gültigkeit
    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
        return '<div class="error-message">Benutzername entspricht nicht den Anforderungen!</div>';
    }

    // Überprüfen der Passwort-Komplexität
    $password_validation = validate_password($_POST['password']);
    if ($password_validation !== true) {
        return $password_validation;
    }

    // Überprüfen, ob der Benutzername oder die E-Mail-Adresse bereits existiert
    if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ? OR email = ?')) {
        $stmt->bind_param('ss', $_POST['username'], $_POST['email']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            if ($is_update) {
                // Benutzername existiert, Account aktualisieren
                $stmt->bind_result($user_id);
                $stmt->fetch();

                if ($stmt = $con->prepare('UPDATE accounts SET password = ?, email = ? WHERE id = ?')) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->bind_param('ssi', $password, $_POST['email'], $user_id);
                    $stmt->execute();
                    return '<div class="success-message">Benutzer erfolgreich aktualisiert!</div>';
                } else {
                    return '<div class="error-message">Kann das Statement nicht erstellen, kontaktiere den Systemadministrator (2)!</div>';
                }
            } else {
                return '<div class="error-message">Benutzername oder E-Mail-Adresse gibt es schon!</div>';
            }
        } else {
            if (!$is_update) {
                // Benutzername existiert nicht, neuen Account erstellen
                if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $uniqid = uniqid();
                    $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
                    $stmt->execute();

                    // Aktivierungs-E-Mail senden
                    $from = 'noreply@bad-timing.eu';
                    $subject = 'Account Aktivierung Erforderlich';
                    $headers = 'From: ' . $from . "\r\n" .
                        'Reply-To: ' . $from . "\r\n" .
                        'X-Mailer: PHP/' . phpversion() . "\r\n" .
                        'MIME-Version: 1.0' . "\r\n" .
                        'Content-Type: text/html; charset=UTF-8' . "\r\n";
                    $activate_link = 'https://bad-timing.eu/cms/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
                    $message = '<p>Bitte klicke auf den folgenden Link zum Aktivieren deines Accounts: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
                    mail($_POST['email'], $subject, $message, $headers);

                    return '<div class="success-message">Überprüfe dein E-Mail-Postfach zum Aktivieren!</div>';
                } else {
                    return '<div class="error-message">Kann das Statement nicht erstellen, kontaktiere den Systemadministrator (3)!</div>';
                }
            } else {
                return '<div class="error-message">Benutzername existiert nicht, daher kein Update möglich!</div>';
            }
        }
        $stmt->close();
    } else {
        return '<div class="error-message">Kann das Statement nicht erstellen, kontaktiere den Systemadministrator (4)!</div>';
    }

    $con->close();
}

function update_user_data($con, $user_id, $new_password, $new_email) {
    // Sicherstellen, dass das Passwort die Mindestanforderungen erfüllt
    $password_validation = validate_password($new_password);
    if ($password_validation !== true) {
        return $password_validation;
    }

    // Validierung der E-Mail-Adresse
    $email_validation = validate_email($new_email);
    if ($email_validation !== true) {
        return $email_validation;
    }

    // Passwort hashen, bevor es in die Datenbank gespeichert wird
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update-Statement vorbereiten
    $stmt = $con->prepare('UPDATE accounts SET password = ?, email = ? WHERE id = ?');
    if (!$stmt) {
        return '<div class="error-message">Fehler beim Vorbereiten des SQL-Statements!</div>';
    }

    $stmt->bind_param('ssi', $hashed_password, $new_email, $user_id);

    // Ausführen und Ergebnis zurückgeben
    if ($stmt->execute()) {
        $stmt->close();
        return '<div class="success-message">Daten erfolgreich aktualisiert!</div>';
    } else {
        $stmt->close();
        return '<div class="error-message">Fehler beim Aktualisieren der Daten!</div>';
    }
}

function validate_email($email) {
    // Überprüfen, ob die E-Mail-Adresse gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '<div class="error-message">Dies ist keine gültige E-Mail-Adresse!</div>';
    }
    return true;
}

function validate_password($password) {
    // Passwort-Komplexitätsanforderungen: mindestens 8 Zeichen, eine Zahl, ein Großbuchstabe, ein Kleinbuchstabe und ein Sonderzeichen
    if (strlen($password) < 8) {
        return '<div class="error-message">Passwort muss mindestens 8 Zeichen lang sein!</div>';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return '<div class="error-message">Passwort muss mindestens einen Großbuchstaben enthalten!</div>';
    }
    if (!preg_match('/[a-z]/', $password)) {
        return '<div class="error-message">Passwort muss mindestens einen Kleinbuchstaben enthalten!</div>';
    }
    if (!preg_match('/[0-9]/', $password)) {
        return '<div class="error-message">Passwort muss mindestens eine Zahl enthalten!</div>';
    }
    if (!preg_match('/[\W]/', $password)) {
        return '<div class="error-message">Passwort muss mindestens ein Sonderzeichen enthalten!</div>';
    }
    return true;
}

function deactivate_user($con, $user_id) {
    $stmt = $con->prepare('UPDATE accounts SET status = ? WHERE id = ?');
    $status = 'deactivated';
    $stmt->bind_param('si', $status, $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}


function reactivate_user($con, $user_id) {
    $stmt = $con->prepare('UPDATE accounts SET status = ? WHERE id = ?');
    $status = 'active';
    $stmt->bind_param('si', $status, $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}


function add_user($con, $username, $password, $email, $role) {
    // Überprüfen, ob die Eingaben gültig sind
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        return '<div class="error-message">Bitte alle Felder ausfüllen!</div>';
    }

    // E-Mail-Überprüfung
    $email_validation = validate_email($email);
    if ($email_validation !== true) {
        return $email_validation;
    }

    // Passwort-Komplexität überprüfen
    $password_validation = validate_password($password);
    if ($password_validation !== true) {
        return $password_validation;
    }

    // Überprüfen, ob der Benutzername bereits existiert
    if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return '<div class="error-message">Benutzername gibt es schon!</div>';
        } else {
            // Überprüfen, ob die E-Mail-Adresse bereits existiert
            if ($stmt = $con->prepare('SELECT id FROM accounts WHERE email = ?')) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    return '<div class="error-message">E-Mail-Adresse ist bereits registriert!</div>';
                } else {
                    // Benutzername existiert nicht, Konto anlegen
                    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, role, activation_code) VALUES (?, ?, ?, ?, ?)')) {
                        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                        $uniqid = uniqid();
                        $stmt->bind_param('sssss', $username, $password_hashed, $email, $role, $uniqid);
                        $stmt->execute();

                        // Aktivierungs-E-Mail senden
                        $from = 'noreply@bad-timing.eu';
                        $subject = 'Account Aktivierung Erforderlich';
                        $headers = 'From: ' . $from . "\r\n" .
                            'Reply-To: ' . $from . "\r\n" .
                            'X-Mailer: PHP/' . phpversion() . "\r\n" .
                            'MIME-Version: 1.0' . "\r\n" .
                            'Content-Type: text/html; charset=UTF-8' . "\r\n";
                        $activate_link = 'https://bad-timing.eu/cms/activate.php?email=' . $email . '&code=' . $uniqid;
                        $message = '<p>Bitte klicke auf den folgenden Link zum Aktivieren deines Accounts: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
                        mail($email, $subject, $message, $headers);

                        return '<div class="success-message">Überprüfe dein E-Mail-Postfach zum Aktivieren!</div>';
                    } else {
                        return '<div class="error-message">Kann das Statement nicht erstellen, kontaktiere den Systemadministrator!</div>';
                    }
                }
            }
        }
        $stmt->close();
    } else {
        return '<div class="error-message">Kann das Statement nicht erstellen, kontaktiere den Systemadministrator!</div>';
    }
}
function update_email($con, $user_id, $new_email) {
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        return '<div class="error-message">Dies ist keine gültige E-Mail-Adresse!</div>';
    }

    $stmt = $con->prepare('UPDATE accounts SET email = ? WHERE id = ?');
    $stmt->bind_param('si', $new_email, $user_id);

    if ($stmt->execute()) {
        return '<div class="success-message">E-Mail-Adresse erfolgreich geändert!</div>';
    } else {
        return '<div class="error-message">Fehler beim Aktualisieren der E-Mail-Adresse!</div>';
    }
}

function update_password($con, $user_id, $new_password) {
    // Passwort-Komplexität überprüfen
    $password_validation = validate_password($new_password);
    if ($password_validation !== true) {
        return $password_validation;
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?');
    $stmt->bind_param('si', $hashed_password, $user_id);

    if ($stmt->execute()) {
        return '<div class="success-message">Passwort erfolgreich geändert!</div>';
    } else {
        return '<div class="error-message">Fehler beim Aktualisieren des Passworts!</div>';
    }
}
function handle_user_actions($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['user_ids'])) {
            $status_messages = [];
            $has_error = false;

            foreach ($_POST['user_ids'] as $user_id) {
                // Rolle und Status des Benutzers überprüfen
                $user_info_sql = "SELECT role, status FROM accounts WHERE id = ?";
                $stmt = $con->prepare($user_info_sql);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $user_info_result = $stmt->get_result();
                $user_info_row = $user_info_result->fetch_assoc();
                $user_role = $user_info_row['role'];
                $user_status = $user_info_row['status'];

                $new_role = $_POST['new_role'][$user_id];
                $deactivate = isset($_POST['deactivate_user_ids'][$user_id]);
                $reactivate = isset($_POST['reactivate_user_ids'][$user_id]);
                $delete = isset($_POST['delete_user_ids'][$user_id]);

                // Aktionen durchführen und Statusmeldungen sammeln
                if ($deactivate && $user_status !== 'deactivated') {
                    if (deactivate_user($con, $user_id)) {
                        $status_messages[] = "Benutzer ID $user_id wurde deaktiviert.";
                    } else {
                        $status_messages[] = "Fehler beim Deaktivieren von Benutzer ID $user_id.";
                        $has_error = true;
                    }
                } elseif ($reactivate && $user_status === 'deactivated') {
                    if (reactivate_user($con, $user_id)) {
                        $status_messages[] = "Benutzer ID $user_id wurde aktiviert.";
                    } else {
                        $status_messages[] = "Fehler beim Aktivieren von Benutzer ID $user_id.";
                        $has_error = true;
                    }
                }

                if ($new_role !== $user_role) {
                    if (update_user_role($con, $user_id, $new_role)) {
                        $status_messages[] = "Rolle von Benutzer ID $user_id wurde auf $new_role geändert.";
                    } else {
                        $status_messages[] = "Fehler beim Ändern der Rolle von Benutzer ID $user_id.";
                        $has_error = true;
                    }
                }

                if ($delete) {
                    if (delete_user($con, $user_id)) {
                        $status_messages[] = "Benutzer ID $user_id wurde gelöscht.";
                    } else {
                        $status_messages[] = "Fehler beim Löschen von Benutzer ID $user_id.";
                        $has_error = true;
                    }
                }
            }

            // Statusnachrichten in der Session speichern
            $_SESSION['status_message'] = implode('<br>', $status_messages);
            $_SESSION['status_type'] = $has_error ? 'error' : 'success';

            // Umleiten, um das Formular zu "resetten" und doppelte Aktionen zu verhindern
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['status_message'] = 'Es wurden keine Benutzer ausgewählt.';
            $_SESSION['status_type'] = 'error';
            // Weiterleitung hinzufügen
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

function update_user_status($con, $user_id, $status) {
    $update_sql = "UPDATE accounts SET status = ? WHERE id = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param('si', $status, $user_id);
    if ($stmt->execute() !== TRUE) {
        display_error('Fehler beim Aktualisieren des Status für Benutzer ID: ' . $user_id);
    } else {
        display_success('Status für Benutzer mit ID ' . $user_id . ' erfolgreich aktualisiert.');
    }
}

function update_user_role($con, $user_id, $new_role) {
    $stmt = $con->prepare('UPDATE accounts SET role = ? WHERE id = ?');
    $stmt->bind_param('si', $new_role, $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

function delete_user($con, $user_id) {
    $stmt = $con->prepare('DELETE FROM accounts WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

function display_error($message) {
    echo '<div class="error-message">' . $message . '</div>';
}

function display_success($message) {
    echo '<div class="success-message">' . $message . '</div>';
}
?>