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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="hamburger-menu">
    <input id="menu__toggle" type="checkbox" />
    <label class="menu__btn" for="menu__toggle">
      <span></span>
    </label>

    <ul class="menu__box">
      <li><a class="menu__item" href="dashboard.php">Home</a></li>
			<li><a class="menu__item" href="Klantenbestand.php">Klantenbestand</a></li>
			<li><a class="menu__item" href="Agenda.php">Agenda</a></li>
			<li><a class="menu__item" href="medewerker.php">Medewerkers</a></li>
    </ul>
  </div>

    <main>
        <h1>Welkom op je dashboard, <?php echo($_SESSION['Gebruiker']); ?>!</h1>
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
