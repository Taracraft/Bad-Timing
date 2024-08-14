// Funktion zum Erstellen und Einfügen des Email ändern Modals in den DOM
function createEmailModal() {
    let emailModalHTML = `
    <div id="emailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('emailModal')">&times;</span>
            <h2>Email Adresse ändern</h2>
            <form action="update_email.php" method="post" id="emailForm">
                <input type="hidden" name="user_id" id="emailUserId">
                <label for="new_email">Neue E-Mail-Adresse:</label>
                <input type="email" name="new_email" id="newEmail" required>
                <input type="submit" value="E-Mail ändern">
            </form>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', emailModalHTML);
}

// Funktion zum Erstellen und Einfügen des Passwort ändern Modals in den DOM
function createPasswordModal() {
    let passwordModalHTML = `
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('passwordModal')">&times;</span>
            <h2>Passwort ändern</h2>
            <form action="update_password.php" method="post" id="passwordForm">
                <input type="hidden" name="user_id" id="passwordUserId">
                <label for="new_password">Neues Passwort:</label>
                <input type="password" name="new_password" id="newPassword" required>
                <input type="submit" value="Passwort ändern">
            </form>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', passwordModalHTML);
}

// Modals erstellen, sobald das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', function() {
    createEmailModal();
    createPasswordModal();

    // Funktion zum Öffnen der E-Mail-Modalbox
    window.openEmailModal = function(userId, currentEmail) {
        document.getElementById('emailUserId').value = userId;
        document.getElementById('newEmail').value = currentEmail;
        document.getElementById('emailModal').style.display = 'block';
    };

    // Funktion zum Öffnen der Passwort-Modalbox
    window.openPasswordModal = function(userId) {
        document.getElementById('passwordUserId').value = userId;
        document.getElementById('passwordModal').style.display = 'block';
    };

    // Funktion zum Schließen der Modalbox
    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none';

        // Optional: Modal-Inhalt zurücksetzen, um sicherzustellen, dass es bei der nächsten Verwendung frisch ist
        let modalContent = document.querySelector(`#${modalId} .modal-content`);
        if (modalContent) {
            modalContent.innerHTML = modalContent.innerHTML; // Dies ist eine einfache Möglichkeit, den Inhalt zu "neuladen"
        }
    };

    // Checkbox zum Auswählen aller Benutzer
    document.getElementById('select_all').onclick = function() {
        var checkboxes = document.querySelectorAll('.user_checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    };

    // E-Mail-Formular absenden
    document.getElementById('emailForm').onsubmit = function(event) {
        event.preventDefault();
        var form = this;

        fetch('update_email.php', {
            method: 'POST',
            body: new FormData(form)
        })
            .then(response => response.text())
            .then(data => {
                document.querySelector('#emailModal .modal-content').innerHTML = data;
                setTimeout(() => closeModal('emailModal'), 3000);
            })
            .catch(error => console.error('Fehler:', error));
    };

    // Passwort-Formular absenden
    document.getElementById('passwordForm').onsubmit = function(event) {
        event.preventDefault();
        var form = this;

        fetch('update_password.php', {
            method: 'POST',
            body: new FormData(form)
        })
            .then(response => response.text())
            .then(data => {
                document.querySelector('#passwordModal .modal-content').innerHTML = data;
                setTimeout(() => closeModal('passwordModal'), 3000);
            })
            .catch(error => console.error('Fehler:', error));
    };
});
