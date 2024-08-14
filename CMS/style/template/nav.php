<body class="loggedin">
<nav class="navbar">
    <ul class="nav-list">
        <li class="nav-item"><a href="../../cms/home.php">Home</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
                <a href="#">Benutzerverwaltung</a>
                <ul class="dropdown">
                    <li><a href="../../cms/admin/acc_add.php">Benutzer hinzufügen</a></li>
                    <li><a href="../../cms/admin/acc_list.php">Benutzer bearbeiten / löschen</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <li class="nav-item"><a href="../../cms/profile.php">Profile</a></li>
        <li class="nav-item"><a href="../../cms/logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Linke Navigationsleiste -->
<nav class="side-navbar left-navbar">
    <ul class="side-nav-list">
        <li class="side-nav-item"><a href="#">Beispiel Links 2</a></li>
        <li class="side-nav-item"><a href="#">Beispiel Links 3</a></li>
        <li class="side-nav-item"><a href="#">Beispiel Links 4</a></li>
    </ul>
</nav>

<!-- Rechte Navigationsleiste -->
<nav class="side-navbar right-navbar">
    <ul class="side-nav-list">
        <li class="side-nav-item"><a href="#">Beispiel Rechts 2</a></li>
        <li class="side-nav-item"><a href="#">Beispiel Rechts 3</a></li>
        <li class="side-nav-item"><a href="#">Beispiel Rechts 4</a></li>
    </ul>
</nav>
