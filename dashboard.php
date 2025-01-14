<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="brand">Mijn Dashboard</div>
        <div class="hamburger" onclick="toggleMenu()">
            &#9776;
        </div>
        <div class="menu" id="menu">
            <a href="dashboard.php">Home</a>
            <a href="Medewerker.php">Medewerkers</a>
            <a href="instellingen.php">Instellingen</a>
            <a href="index.php">Uitloggen</a>
            <a href="klantenbestand.php">Klantenbestand</a>
                </div>
    </div>

    <main>
        <h1>Welkom op je dashboard, <?php echo htmlspecialchars($_SESSION['Gebruiker']); ?>!</h1>
        <p>Gebruik het menu hierboven om te navigeren.</p>
    </main>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>
