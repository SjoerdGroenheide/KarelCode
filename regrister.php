<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de formuliergegevens
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $account_type = $_POST['account_type'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Sanitize input om SQL-injecties te voorkomen
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($pass);
    $account_type = $conn->real_escape_string($account_type);
    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);
    $email = $conn->real_escape_string($email);
    $phone = $conn->real_escape_string($phone);

    // Hash het wachtwoord
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Voeg de gebruiker toe aan de database
    $sql = "INSERT INTO users (username, password, account_type, first_name, last_name, email, phone)
            VALUES ('$user', '$hashed_password', '$account_type', '$first_name', '$last_name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Account succesvol aangemaakt!";
    } else {
        echo "Fout: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie</title>
</head>
<body>
    <h2>Registreren</h2>
    <form action="register.php" method="POST">
        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="account_type">Accounttype:</label><br>
        <select id="account_type" name="account_type" required>
            <option value="admin">Admin</option>
            <option value="user">Gebruiker</option>
        </select><br><br>

        <label for="first_name">Voornaam:</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Achternaam:</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">E-mailadres:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Telefoonnummer:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <input type="submit" value="Registreren">
    </form>
</body>
</html>
