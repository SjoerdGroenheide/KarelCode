<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Verbinden met de database
$host = "localhost"; // Database host
$username = "root";  // Database gebruikersnaam
$password = "";      // Database wachtwoord
$dbname = "karelveenstra"; // Database naam

$conn = new mysqli($host, $username, $password, $dbname);

// Controleer databaseverbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Verwijder een medewerkerD
if (isset($_GET['verwijder'])) {
    $userid = intval($_GET['verwijder']);
    $deleteQuery = "DELETE FROM gebruikers WHERE UserID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->close();
    header("Location: Medewerker.php");
    exit;
}

// Voeg een nieuwe medewerker toe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toevoegen'])) {
    $gebruiker = $_POST['gebruiker'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT); // Hash het wachtwoord
    $account_type = $_POST['account_type'];
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $telefoonnummer = $_POST['telefoonnummer'];

    $insertQuery = "INSERT INTO gebruikers (Gebruiker, Wachtwoord, Account_type, Voornaam, Achternaam, Email, TelefoonNummer) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssssss", $gebruiker, $wachtwoord, $account_type, $voornaam, $achternaam, $email, $telefoonnummer);

    if ($stmt->execute()) {
        header("Location: Medewerker.php");
        exit;
    } else {
        echo "Fout bij het toevoegen van medewerker: " . $conn->error;
    }

    $stmt->close();
}

// Haal alle medewerkers op
$query = "SELECT * FROM gebruikers";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerkers</title>
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
        <h1>Medewerkerslijst</h1>
        <table>
            <thead>
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>Email</th>
                    <th>Telefoonnummer</th>
                    <th>Functie</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ($row['Gebruiker']); ?></td>
                            <td><?php echo ($row['Voornaam']); ?></td>
                            <td><?php echo ($row['Achternaam']); ?></td>
                            <td><?php echo ($row['Email']); ?></td>
                            <td><?php echo ($row['TelefoonNummer']); ?></td>
                            <td><?php echo ($row['Account_type']); ?></td>
                            <td>
                                <a class="delete-btn" href="Medewerker.php?verwijder=<?php echo $row['UserID']; ?>" onclick="return confirm('Weet je zeker dat je deze medewerker wilt verwijderen?');">Verwijderen</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Geen medewerkers gevonden.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Voeg een nieuwe medewerker toe</h2>
        <form method="POST" action="">
            <label for="gebruiker">Gebruikersnaam:</label>
            <input type="text" id="gebruiker" name="gebruiker" required>

            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required>

            <label for="account_type">Functie:</label>
            <select id="account_type" name="account_type" required>
                <option value="Admin">Admin</option>
                <option value="Medewerker">Medewerker</option>
                <option value="stagiair">Stagiair</option>
            </select>

            <label for="voornaam">Voornaam:</label>
            <input type="text" id="voornaam" name="voornaam" required>

            <label for="achternaam">Achternaam:</label>
            <input type="text" id="achternaam" name="achternaam" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefoonnummer">Telefoonnummer:</label>
            <input type="text" id="telefoonnummer" name="telefoonnummer" required>

            <button type="submit" name="toevoegen">Toevoegen</button>
        </form>
    </main>
</body>
</html>

<?php
$conn->close();
?>
