<?php
session_start();

// Verbindingsinformatie
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "karelveenstra";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de gebruikersinvoer
    $user = $_POST['Gebruiker'];
    $pass = $_POST['Wachtwoord'];

    //  SQL-injecties te voorkomen
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($pass);

    // Query om de gebruiker te vinden
    $sql = "SELECT UserID, Gebruiker, Wachtwoord FROM gebruikers WHERE Gebruiker='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vergelijk het ingevoerde wachtwoord met het wachtwoord in de database (platte tekst)
        if ($pass == $row['Wachtwoord']) {
            // Start een sessie en sla gebruikersgegevens op
            $_SESSION['loggedin'] = true;
            $_SESSION['Gebruiker'] = $user;

            // Doorsturen naar dashboard.php
            header("Location: dashboard.php");
            exit; // Zorg ervoor dat de script verder niet uitgevoerd wordt na de redirect
        } else {
            $error_message = "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        $error_message = "Ongeldige gebruikersnaam of wachtwoord.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Systeem</title>
</head>
<body>
    <h2>Login</h2>
    <form action="index.php" method="POST">
        <label for="Gebruiker">Gebruikersnaam:</label><br>
        <input type="text" id="Gebruiker" name="Gebruiker" required><br><br>

        <label for="Wachtwoord">Wachtwoord:</label><br>
        <input type="password" id="Wachtwoord" name="Wachtwoord" required><br><br>

        <input type="submit" value="Inloggen">
    </form>

    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>
</body>
</html>
