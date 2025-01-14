<?php
session_start();

// Databaseverbinding instellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "karelveenstra";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Verwerk formulierinvoer (POST-aanvraag)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Als er een afspraak verwijderd moet worden
    if ($_POST['action'] == 'verwijder' && isset($_POST['afspraak_id'])) {
        $afspraakID = (int)$_POST['afspraak_id'];
        
        // Verwijder de afspraak uit de database
        $sql = "DELETE FROM afspraak WHERE AfspraakID = $afspraakID";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Afspraak succesvol verwijderd!";
        } else {
            $_SESSION['message'] = "Fout bij verwijderen: " . $conn->error;
        }
    }
}

// Haal afspraken op uit de database
$sql = "SELECT a.AfspraakID, a.Title, a.Body, a.Status, g.gebruiker AS Medewerker, k.Voornaam AS Klant
        FROM afspraak a
        LEFT JOIN gebruikers g ON a.UserID = g.UserID
        LEFT JOIN klanten k ON a.KlantID = k.KlantID";

$result = $conn->query($sql);

// Haal medewerkers en klanten op voor de select-opties in het formulier
$users = $conn->query("SELECT UserID, gebruiker FROM gebruikers");
$klanten = $conn->query("SELECT KlantID, voornaam FROM klanten");

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Agenda</title>
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

<h2>Agenda</h2>

<!-- Feedback Bericht -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>

<!-- Formulier om een afspraak toe te voegen -->
<form action="agenda.php" method="POST">
    <label for="title">Titel:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="body">Beschrijving:</label><br>
    <textarea id="body" name="body" rows="4" required></textarea><br><br>

    <label for="user_id">Medewerker:</label><br>
    <select id="user_id" name="user_id" required>
        <?php
        while ($row = $users->fetch_assoc()) {
            echo "<option value='" . $row['UserID'] . "'>" . $row['gebruiker'] . "</option>";
        }
        ?>
    </select><br><br>

    <label for="klant_id">Klant:</label><br>
    <select id="klant_id" voornaam="klant_id" required>
        <?php
        while ($row = $klanten->fetch_assoc()) {
            echo "<option value='" . $row['KlantID'] . "'>" . $row['voornaam'] . "</option>";
        }
        ?>
    </select><br><br>

    <label for="status">Status:</label><br>
    <select id="status" name="status" required>
        <option value="Open">Open</option>
        <option value="Gesloten">Gesloten</option>
    </select><br><br>

    <input type="submit" value="Afspraak toevoegen">
</form>

<hr>

<!-- Overzicht van afspraken -->
<h3>Bestaande Afspraken</h3>
<table border="1">
    <tr>
        <th>Titel</th>
        <th>Beschrijving</th>
        <th>Medewerker</th>
        <th>Klant</th>
        <th>Status</th>
        <th>Acties</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Title'] . "</td>
                    <td>" . $row['Body'] . "</td>
                    <td>" . $row['Medewerker'] . "</td>
                    <td>" . $row['Klant'] . "</td>
                    <td>" . $row['Status'] . "</td>
                    <td>
                        <form action='agenda.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='afspraak_id' value='" . $row['AfspraakID'] . "' />
                            <input type='hidden' name='action' value='verwijder' />
                            <input type='submit' value='Verwijder' onclick='return confirm(\"Weet je zeker dat je deze afspraak wilt verwijderen?\");' />
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Geen afspraken gevonden.</td></tr>";
    }
    ?>
</table>

</body>
</html>
