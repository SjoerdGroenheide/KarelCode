<?php
session_start();

// Database configuratie
$host = "localhost";
$dbname = "karelveenstra";
$username = "root"; // Pas aan indien nodig
$password = ""; // Vul je databasewachtwoord in

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met database: " . $e->getMessage());
}

// Functie om klanten weer te geven
function klantenWeergeven($pdo) {
    $stmt = $pdo->query("SELECT * FROM klanten");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Formulierverwerking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $actie = $_POST['actie'];

    if ($actie == 'toevoegen') {
        $stmt = $pdo->prepare("INSERT INTO klanten (Voornaam, Achternaam, TelefoonNummer, Straat, Huisnummer, Postcode, Plaats, Email)
            VALUES (:voornaam, :achternaam, :telefoonnummer, :straat, :huisnummer, :postcode, :plaats, :email)");
        $stmt->execute([
            'voornaam' => $_POST['voornaam'],
            'achternaam' => $_POST['achternaam'],
            'telefoonnummer' => $_POST['telefoonnummer'],
            'straat' => $_POST['straat'],
            'huisnummer' => $_POST['huisnummer'],
            'postcode' => $_POST['postcode'],
            'plaats' => $_POST['plaats'],
            'email' => $_POST['email']
        ]);
    } elseif ($actie == 'verwijderen') {
        $stmt = $pdo->prepare("DELETE FROM klanten WHERE KlantID = :klantid");
        $stmt->execute(['klantid' => $_POST['klantid']]);
    } elseif ($actie == 'aanpassen') {
        $stmt = $pdo->prepare("UPDATE klanten SET Voornaam = :voornaam, Achternaam = :achternaam, TelefoonNummer = :telefoonnummer,
            Straat = :straat, Huisnummer = :huisnummer, Postcode = :postcode, Plaats = :plaats, Email = :email WHERE KlantID = :klantid");
        $stmt->execute([
            'voornaam' => $_POST['voornaam'],
            'achternaam' => $_POST['achternaam'],
            'telefoonnummer' => $_POST['telefoonnummer'],
            'straat' => $_POST['straat'],
            'huisnummer' => $_POST['huisnummer'],
            'postcode' => $_POST['postcode'],
            'plaats' => $_POST['plaats'],
            'email' => $_POST['email'],
            'klantid' => $_POST['klantid']
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenbeheer</title>
    <style>
/* Basisstijlen voor hamburgermenu */
.menu-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #333;
    padding: 10px;
    position: relative; /* Voeg deze regel toe voor positie */
}

.menu-bar .menu {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: absolute;
    top: 50px;
    right: 0;
    background-color: #333;
    width: 200px; /* Pas de breedte aan naar wens */
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
    visibility: hidden; /* Menu is verborgen standaard */
}

.menu-bar .menu a {
    color: white;
    padding: 10px;
    text-decoration: none;
    text-align: center;
    display: block;
    width: 100%;
}

.menu-bar .menu a:hover {
    background-color: #575757;
}

.hamburger {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    width: 30px;
    height: 30px;
    background-color: transparent;
    border: none;
    cursor: pointer;
}

.hamburger div {
    width: 30px;
    height: 5px;
    background-color: white;
}

/* Zorg ervoor dat het menu zichtbaar is op kleinere schermen */
@media screen and (max-width: 768px) {
    .menu-bar .menu {
        display: flex;
        visibility: hidden; /* Blijft standaard verborgen */
    }

    .menu-bar.active .menu {
        visibility: visible; /* Menu wordt zichtbaar als de .active klasse is toegevoegd */
    }
}
    </style>

    <script>
// Functie om het menu in of uit te schakelen
function toggleMenu() {
    const menuBar = document.querySelector('.menu-bar');
    menuBar.classList.toggle('active'); // Zorgt ervoor dat het menu zichtbaar wordt
}
    </script>
</head>
<body>
    <!-- Hamburgermenu -->
    <div class="menu-bar">
        <button class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </button>
        <div class="menu">
            <a href="#">Home</a>
            <a href="#">Klantenlijst</a>
            <a href="#">Klant toevoegen</a>
            <a href="#">Klant verwijderen</a>
            <a href="#">Klant aanpassen</a>
        </div>
    </div>

    <h1>Klantenbeheer</h1>
    <h2>Klantenlijst</h2>
    <ul>
        <?php foreach (klantenWeergeven($pdo) as $klant): ?>
            <li>
                <?= "ID: {$klant['KlantID']}, Naam: {$klant['Voornaam']} {$klant['Achternaam']}, 
                Telefoon: {$klant['TelefoonNummer']}, Adres: {$klant['Straat']} {$klant['Huisnummer']}, 
                {$klant['postcode']} {$klant['Plaats']}, Email: {$klant['Email']}" ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Klant toevoegen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="toevoegen">
        <label>Voornaam: <input type="text" name="voornaam" required></label><br>
        <label>Achternaam: <input type="text" name="achternaam" required></label><br>
        <label>Telefoonnummer: <input type="text" name="telefoonnummer" required></label><br>
        <label>Straat: <input type="text" name="straat" required></label><br>
        <label>Huisnummer: <input type="text" name="huisnummer" required></label><br>
        <label>Postcode: <input type="text" name="postcode" required></label><br>
        <label>Plaats: <input type="text" name="plaats" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Toevoegen</button>
    </form>

    <h2>Klant verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="verwijderen">
        <label>KlantID: <input type="text" name="klantid" required></label><br>
        <button type="submit">Verwijderen</button>
    </form>

    <h2>Klant aanpassen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="aanpassen">
        <label>KlantID: <input type="text" name="klantid" required></label><br>
        <label>Voornaam: <input type="text" name="voornaam" required></label><br>
        <label>Achternaam: <input type="text" name="achternaam" required></label><br>
        <label>Telefoonnummer: <input type="text" name="telefoonnummer" required></label><br>
        <label>Straat: <input type="text" name="straat" required></label><br>
        <label>Huisnummer: <input type="text" name="huisnummer" required></label><br>
        <label>Postcode: <input type="text" name="postcode" required></label><br>
        <label>Plaats: <input type="text" name="plaats" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Aanpassen</button>
    </form>
</body>
</html>
