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
</head>
<body>
    <h1>Welkom op je dashboard, <?php echo htmlspecialchars($_SESSION['Gebruiker']); ?>!</h1>
    <p>Je bent ingelogd!</p>
    <a href="logout.php">Uitloggen</a>
</body>
</html>
