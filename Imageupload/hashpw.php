<?php
/**
 * Wir wollen nur unser Passwort mit dem aktuellen STANDARD-Algorithmus hashen.
 * Dieser ist derzeit BCRYPT und liefert ein Ergebnis mit 60 Zeichen.
 *
 * Beachten Sie, dass sich STANDARD im Laufe der Zeit �ndern kann, daher
 * sollten Sie sich darauf vorbereiten, indem Sie die Speicherung von mehr als
 * 60 Zeichen erm�glichen (255 w�re gut).
 */
echo password_hash ("Minecraft2021", PASSWORD_DEFAULT);
?>
